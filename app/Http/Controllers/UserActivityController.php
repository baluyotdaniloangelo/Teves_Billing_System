<?php

namespace App\Http\Controllers;

use App\Models\UserAccountModel;
use App\Services\UserActivityHistory;
use App\Services\UserActivityService;
use App\Support\UserManagementAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserActivityController extends Controller
{
    private function actor(): UserAccountModel
    {
        $actor = UserManagementAccess::actor();
        abort_unless($actor->user_type === 'SUAdmin', 403, 'Super administrator access is required.');

        return $actor;
    }

    private function filters(Request $request): array
    {
        $filters = $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d', 'end_date' => 'nullable|date_format:Y-m-d',
            'user_id' => 'nullable|integer|min:1', 'module' => 'nullable|string|max:255',
            'action' => ['nullable', Rule::in(array_keys(UserActivityHistory::ACTIONS))],
            'search' => 'nullable|string|max:100', 'page' => 'nullable|integer|min:1|max:1000000',
            'per_page' => ['nullable', Rule::in([20, 50, 100])],
        ]);
        $filters['start_date'] = $filters['start_date'] ?? now()->subDays(6)->toDateString();
        $filters['end_date'] = $filters['end_date'] ?? now()->toDateString();
        $filters['start'] = Carbon::createFromFormat('!Y-m-d', $filters['start_date'], config('app.timezone'))->startOfDay();
        $filters['end'] = Carbon::createFromFormat('!Y-m-d', $filters['end_date'], config('app.timezone'))->endOfDay();
        if ($filters['start']->gt($filters['end']) || $filters['start']->diffInDays($filters['end']) > 365) {
            throw ValidationException::withMessages(['end_date' => 'Choose an end date after the start date, with a range of up to 366 days.']);
        }

        return $filters;
    }

    public function index(Request $request, UserActivityService $audit, UserActivityHistory $history)
    {
        $data = $this->actor(); $filters = $this->filters($request);
        $users = UserAccountModel::withTrashed()->orderBy('user_real_name')->limit(2000)->get(['user_id', 'user_real_name', 'deleted_at']);
        $modules = $audit->available() ? $history->baseQuery()->whereNotNull('log_name')->distinct()->orderBy('log_name')->limit(100)->pluck('log_name') : collect();
        $title = 'User Activity'; $actions = UserActivityHistory::ACTIONS;

        return response()->view('pages.user_activity.index', compact('data', 'filters', 'users', 'modules', 'actions', 'title'))->header('Cache-Control', 'no-store, private');
    }

    public function listing(Request $request, UserActivityService $audit, UserActivityHistory $history)
    {
        $this->actor(); $filters = $this->filters($request);
        if (!$audit->available()) return response()->json(['available' => false, 'recording_enabled' => false, 'data' => [], 'summary' => ['events' => 0, 'users' => 0, 'changes' => 0, 'signins' => 0], 'page' => 1, 'last_page' => 1, 'total' => 0])->header('Cache-Control', 'no-store, private');
        $query = $history->filtered($filters); $summary = $history->summary($query);
        $page = $query->orderByDesc('created_at')->orderByDesc('id')->paginate((int) ($filters['per_page'] ?? 20), ['*'], 'page', (int) ($filters['page'] ?? 1));
        $actors = $history->actorsFor($page->items());

        return response()->json([
            'available' => true, 'recording_enabled' => (bool) config('activitylog.enabled', true),
            'data' => collect($page->items())->map(function ($entry) use ($history, $actors) { return $history->row($entry, $actors); }),
            'summary' => $summary, 'page' => $page->currentPage(), 'last_page' => $page->lastPage(), 'total' => $page->total(),
        ])->header('Cache-Control', 'no-store, private');
    }

    public function detail($activityId, UserActivityService $audit, UserActivityHistory $history)
    {
        $this->actor(); abort_unless($audit->available(), 503, 'Activity history is currently unavailable.');
        $entry = $history->baseQuery()->where('id', $activityId)->first(); abort_unless($entry, 404);

        return response()->json($history->detail($entry))->header('Cache-Control', 'no-store, private');
    }
}
