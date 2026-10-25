<?php

namespace App\Http\Controllers;

use App\Services\UserPresenceService;
use App\Models\UserAccountModel;
use App\Support\UserManagementAccess;

class UserPresenceController extends Controller
{
    public function online(UserPresenceService $presence)
    {
        $actor = UserManagementAccess::actor();
        abort_unless($actor->user_type === 'SUAdmin', 403, 'Super administrator access is required.');
        $users = [];
        if ($presence->available()) {
            $query = UserAccountModel::select('user_id', 'user_real_name', 'user_type');
            $presence->attach($query);
            $presence->filter($query, 'online');
            $users = $query->orderBy('user_real_name')->orderBy('user_id')->limit(10)->get()->map(function ($user) use ($presence) {
                return [
                    'id' => $user->user_id, 'name' => $user->user_real_name, 'role' => $user->user_type,
                    'last_seen_at' => $presence->isoTimestamp($user->last_seen_at),
                ];
            })->all();
        }

        return response()->json([
            'available' => $presence->available(), 'total' => $presence->onlineCount(UserAccountModel::query()),
            'users' => $users, 'window_seconds' => $presence->windowSeconds(),
        ])->header('Cache-Control', 'no-store, private');
    }

    public function heartbeat(UserPresenceService $presence)
    {
        UserManagementAccess::actor();

        // AuthCheck records only the authenticated browser's presence before this action runs.
        return response()->json(['tracking_available' => $presence->available(), 'online_window_seconds' => $presence->windowSeconds()])
            ->header('Cache-Control', 'no-store, private');
    }
}
