<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAccountModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserActivityHistory
{
    public const ACTIONS = [
        'created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted', 'restored' => 'Restored',
        'login' => 'Signed in', 'logout' => 'Signed out', 'viewed' => 'Viewed page', 'submitted' => 'Request submitted',
    ];

    private $audit;

    public function __construct(UserActivityService $audit)
    {
        $this->audit = $audit;
    }

    public function baseQuery()
    {
        return DB::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name', 'activity_log'));
    }

    private function localActors($query): void
    {
        $query->where(function ($query) {
            $query->whereNull('causer_type')->orWhere('causer_type', '')->orWhereIn('causer_type', [User::class, UserAccountModel::class]);
        });
    }

    public function filtered(array $filters)
    {
        $query = $this->baseQuery()->whereBetween('created_at', [$filters['start']->toDateTimeString(), $filters['end']->toDateTimeString()]);
        if (!empty($filters['user_id'])) { $query->where('causer_id', $filters['user_id']); $this->localActors($query); }
        if (!empty($filters['module'])) $query->where('log_name', $filters['module']);
        if (!empty($filters['action'])) $query->where('description', $filters['action']);
        if (!empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $ids = DB::table('user_tb')->where(function ($query) use ($term) {
                $query->where('user_real_name', 'like', $term)->orWhere('user_name', 'like', $term);
            })->pluck('user_id')->all();
            $query->where(function ($query) use ($term, $ids) {
                $query->where('log_name', 'like', $term)->orWhere('subject_id', 'like', $term);
                if ($ids) $query->orWhere(function ($query) use ($ids) { $query->whereIn('causer_id', $ids); $this->localActors($query); });
            });
        }

        return $query;
    }

    public function summary($query): array
    {
        $actors = (clone $query)->whereNotNull('causer_id'); $this->localActors($actors);

        return [
            'events' => (clone $query)->count(), 'users' => $actors->distinct()->count('causer_id'),
            'changes' => (clone $query)->whereIn('description', ['created', 'updated', 'deleted', 'restored'])->count(),
            'signins' => (clone $query)->where('description', 'login')->count(),
        ];
    }

    public function actorsFor($entries): array
    {
        return DB::table('user_tb')->whereIn('user_id', collect($entries)->pluck('causer_id')->filter()->unique()->all())
            ->get(['user_id', 'user_real_name', 'user_name', 'user_type'])->keyBy('user_id')->all();
    }

    private function properties($entry): array
    {
        $properties = json_decode($entry->properties ?: '{}', true);

        return $this->audit->sanitize(is_array($properties) ? $properties : []);
    }

    public function row($entry, array $actors): array
    {
        $properties = $this->properties($entry);
        $local = in_array($entry->causer_type, [null, '', User::class, UserAccountModel::class], true);
        $actor = $local && $entry->causer_id ? ($actors[$entry->causer_id] ?? null) : null;
        $snapshot = $local && isset($properties['_actor']['id']) && (int) $properties['_actor']['id'] === (int) $entry->causer_id ? $properties['_actor'] : [];

        return [
            'id' => $entry->id,
            'occurred_at' => $entry->created_at ? Carbon::parse($entry->created_at, config('app.timezone'))->toIso8601String() : null,
            'user_id' => $local ? $entry->causer_id : null,
            'user_name' => is_string($snapshot['name'] ?? null) ? $snapshot['name'] : ($actor->user_real_name ?? ($entry->causer_id && $local ? 'User #' . $entry->causer_id : 'System / unknown actor')),
            'user_role' => is_string($snapshot['role'] ?? null) ? $snapshot['role'] : ($actor->user_type ?? ''),
            'module' => $entry->log_name ?: 'Application',
            'action' => array_key_exists($entry->description, self::ACTIONS) ? $entry->description : 'other',
            'action_label' => self::ACTIONS[$entry->description] ?? 'Recorded event',
            'record' => $entry->subject_id ? 'Record #' . $entry->subject_id : '—',
        ];
    }

    public function detail($entry): array
    {
        $properties = $this->properties($entry);
        $old = is_array($properties['old'] ?? null) ? $properties['old'] : [];
        $new = is_array($properties['attributes'] ?? null) ? $properties['attributes'] : [];
        $changes = [];
        foreach (array_unique(array_merge(array_keys($old), array_keys($new))) as $field) {
            if (in_array($field, ['created_at', 'updated_at', 'created_by_user_idx', 'updated_by_user_idx', 'deleted_by_user_id', '_omitted'], true)) continue;
            if (!$this->audit->sensitive((string) $field) && array_key_exists($field, $old) && array_key_exists($field, $new) && $old[$field] === $new[$field]) continue;
            $changes[] = ['field' => ucwords(str_replace('_', ' ', $field)), 'before' => $old[$field] ?? null, 'after' => $new[$field] ?? null];
        }

        return array_merge($this->row($entry, $this->actorsFor([$entry])), [
            'changes' => $changes,
            'operation' => is_string($properties['operation'] ?? null) ? ucwords($properties['operation']) : null,
        ]);
    }
}
