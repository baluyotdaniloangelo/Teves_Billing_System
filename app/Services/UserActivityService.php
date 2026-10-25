<?php

namespace App\Services;

use App\Models\UserAccountModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserActivityService
{
    private $available;

    public function available(): bool
    {
        if ($this->available === null) {
            $this->available = Schema::connection(config('activitylog.database_connection'))
                ->hasTable(config('activitylog.table_name', 'activity_log'));
        }

        return $this->available;
    }

    public function enabled(): bool
    {
        return (bool) config('activitylog.enabled', true) && $this->available();
    }

    public function actor(?Request $request = null): ?array
    {
        $request = $request ?: request();
        if (!$request->hasSession() || !$request->session()->has('loginID')) return null;
        $cached = $request->attributes->get('_audit_actor');
        if ($cached && (int) $cached['id'] === (int) $request->session()->get('loginID')) return $cached;
        $user = DB::table('user_tb')->where('user_id', $request->session()->get('loginID'))
            ->first(['user_id', 'user_real_name', 'user_name', 'user_type']);
        $actor = $user ? ['id' => (int) $user->user_id, 'name' => $user->user_real_name, 'username' => $user->user_name, 'role' => $user->user_type] : null;
        $request->attributes->set('_audit_actor', $actor);

        return $actor;
    }

    public function sensitive(string $key): bool
    {
        return (bool) preg_match('/password|passcode|secret|token|api.?key|authorization|cookie|credential|signature|image|blob/i', $key);
    }

    public function sanitize($value, int $depth = 0)
    {
        if ($depth > 6) return '[Nested data omitted]';
        if ($value instanceof \Illuminate\Support\Collection) $value = $value->all();
        if (is_object($value)) $value = (array) $value;
        if (is_array($value)) {
            $clean = [];
            foreach (array_slice($value, 0, 200, true) as $key => $item) {
                $clean[$key] = $this->sensitive((string) $key) ? '[Hidden]' : $this->sanitize($item, $depth + 1);
            }
            if (count($value) > 200) $clean['_omitted'] = count($value) - 200;

            return $clean;
        }
        if (is_string($value)) {
            if (!mb_check_encoding($value, 'UTF-8')) return '[Binary data omitted]';

            return mb_strlen($value) > 2000 ? mb_substr($value, 0, 2000) . '…' : $value;
        }

        return is_scalar($value) || $value === null ? $value : '[Unsupported value]';
    }

    /** Sanitize every newly saved Spatie activity, including existing model events. */
    public function prepare(Model $activity): void
    {
        $properties = $this->sanitize($activity->properties ?: []);
        $actor = $this->actor();
        if (!$actor && $activity->causer_id && in_array($activity->causer_type, [null, '', UserAccountModel::class, \App\Models\User::class], true)) {
            $user = DB::table('user_tb')->where('user_id', $activity->causer_id)->first(['user_id', 'user_real_name', 'user_name', 'user_type']);
            if ($user) $actor = ['id' => (int) $user->user_id, 'name' => $user->user_real_name, 'username' => $user->user_name, 'role' => $user->user_type];
        }
        if ($actor) {
            $activity->causer_id = $actor['id'];
            $activity->causer_type = UserAccountModel::class;
            $properties['_actor'] = $actor;
        } else {
            unset($properties['_actor']);
        }
        $route = request()->route();
        if ($route) $properties['_route'] = $route->getName();
        $activity->properties = collect($properties);
        request()->attributes->set('_audit_recorded', true);
    }

    public function record(string $module, string $action, array $properties = [], ?Model $subject = null): bool
    {
        if (!$this->enabled() || !$this->actor()) return false;
        try {
            $logger = activity($module)->withProperties($this->sanitize($properties));
            if ($subject) $logger->performedOn($subject);
            $logger->log($action);

            return true;
        } catch (\Throwable $exception) {
            // An unavailable audit store must not discard a completed billing operation.
            Log::warning('User activity could not be recorded.', ['module' => $module, 'action' => $action]);

            return false;
        }
    }

    public function recordRequest(Request $request, $response): void
    {
        if (!$this->enabled() || !$this->actor($request) || $request->attributes->get('_audit_recorded')) return;
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) return;
        $route = $request->route();
        if (!$route || !$route->getName() || $request->is('user/presence/*', 'user/activity*', 'login-user', 'logout')) return;
        $method = $route->getActionMethod();
        $operation = trim(str_replace('_', ' ', Str::snake($method)));
        if ($request->isMethod('GET')) {
            if ($request->ajax() || !str_contains((string) $response->headers->get('Content-Type'), 'text/html')) return;
            $action = 'viewed';
        } else {
            if (in_array($request->method(), ['HEAD', 'OPTIONS'], true) || $request->has('draw')) return;
            // Legacy AJAX reads frequently use POST; avoid recording polling as user activity.
            if (preg_match('/(^|_)(get|list|info|information|fetch|search|options|count|filter|preview|summary|chart|print|export|generate|validate)(_|$)/i', Str::snake($method))) return;
            $action = 'submitted';
        }
        $controller = class_basename(strstr($route->getActionName(), '@', true) ?: $route->getActionName());
        $module = ucwords(str_replace('_', ' ', Str::snake(preg_replace('/Controller$/', '', $controller))));
        $this->record($module ?: 'Application', $action, ['operation' => $operation, 'route' => $route->getName(), 'method' => $request->method()]);
    }
}
