<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\Mail\ResetPassword;
use App\Models\UserAccountModel;
use App\Services\UserPresenceService;
use App\Support\UserManagementAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $presence;

    public function __construct(UserPresenceService $presence)
    {
        $this->presence = $presence;
    }

    public function user()
    {
        $data = UserManagementAccess::manager();
        $summary = $this->summary();

        return view('pages.user', compact('data', 'summary'))->with('title', 'User Management');
    }

    public function getUserList(Request $request)
    {
        $canMonitorPresence = UserManagementAccess::manager()->user_type === 'SUAdmin';
        abort_if(!$canMonitorPresence && $request->filled('presence'), 403, 'Super administrator access is required.');
        $query = UserManagementAccess::visibleUsers()->select([
            'user_id', 'user_job_title', 'user_real_name', 'user_name', 'user_type',
            'user_status', 'user_email_address', 'user_branch_access_type', 'created_at', 'updated_at',
        ]);
        if ($canMonitorPresence) $this->presence->attach($query);
        $request->validate([
            'role' => ['nullable', Rule::in(['SUAdmin', 'Admin', 'Supervisor', 'Accounting_Staff', 'Encoder'])],
            'status' => ['nullable', Rule::in(['Active', 'Inactive'])],
            'presence' => ['nullable', Rule::in(['online', 'offline'])],
        ]);
        $query->when($request->filled('role'), function ($query) use ($request) {
            $query->where('user_type', $request->role);
        })->when($request->filled('status'), function ($query) use ($request) {
            $query->where('user_status', $request->status);
        });
        if ($canMonitorPresence) $this->presence->filter($query, $request->presence);

        $table = DataTables::of($query)->addIndexColumn()
            ->addColumn('action', function ($user) use ($canMonitorPresence) {
                return view('pages.user.partials.actions', compact('user'))->with('canViewActivity', $canMonitorPresence)->render();
            })
            ->addColumn('created_at_dt_format', function ($user) { return $user->getRawOriginal('created_at'); })
            ->addColumn('updated_at_dt_format', function ($user) { return $user->getRawOriginal('updated_at'); })
            // Text columns are escaped by the client renderer; only the action partial is HTML.
            ->escapeColumns([])->rawColumns(['action'])->with('summary', $this->summary());
        if ($canMonitorPresence) {
            $table->editColumn('is_online', function ($user) { return $user->is_online === null ? null : (bool) $user->is_online; })
                ->editColumn('last_seen_at', function ($user) { return $this->presence->isoTimestamp($user->last_seen_at); })
                ->with('presence', ['available' => $this->presence->available(), 'window_seconds' => $this->presence->windowSeconds()]);
        }

        return $table->make(true)->header('Cache-Control', 'no-store, private');
    }

    public function user_info(Request $request)
    {
        $request->validate(['UserID' => 'required|integer']);
        $actor = UserManagementAccess::actor();
        $user = (int) $request->UserID === (int) $actor->user_id
            ? $actor : UserManagementAccess::target($request->UserID);

        return response()->json($user->only([
            'user_id', 'user_name', 'user_real_name', 'user_type', 'user_status',
            'user_job_title', 'user_email_address', 'user_branch_access_type',
        ]));
    }

    public function create_user_post(SaveUserRequest $request)
    {
        $user = new UserAccountModel();
        $this->fillUser($user, $request->validated());
        $user->created_by_user_idx = session('loginID');
        $user->save();
        $warning = $this->sendCredentials($user, $request->user_password, true);

        return response()->json([
            'success' => 'User account created successfully.', 'user_id' => $user->user_id, 'warning' => $warning,
        ], 201);
    }

    public function update_user_post(SaveUserRequest $request)
    {
        $user = UserManagementAccess::target($request->userID);
        if ((int) $user->user_id === (int) session('loginID') &&
            ($request->user_status !== 'Active' || $request->user_type !== $user->user_type)) {
            throw ValidationException::withMessages(['user_type' => 'Keep your own role and active status to retain administrator access.']);
        }
        $this->fillUser($user, $request->validated());
        $user->updated_by_user_idx = session('loginID');
        $user->save();
        if ((int) $user->user_id === (int) session('loginID')) {
            session(['UserType' => $user->user_type, 'user_branch_access_type' => $user->user_branch_access_type]);
        }
        $warning = $request->filled('user_password') ? $this->sendCredentials($user, $request->user_password) : null;

        return response()->json(['success' => 'User account updated successfully.', 'warning' => $warning]);
    }

    public function delete_user_confirmed(Request $request)
    {
        $request->validate(['userID' => 'required|integer']);
        $user = UserManagementAccess::target($request->userID);
        abort_if((int) $user->user_id === (int) session('loginID'), 422, 'You cannot delete your own account.');
        $user->delete();

        return response()->json(['success' => 'User account deleted successfully.']);
    }

    public function user_account_post(Request $request)
    {
        $user = UserManagementAccess::actor();
        $request->validate(['userID' => 'required|integer']);
        abort_unless((int) $request->userID === (int) $user->user_id, 403);
        $values = $request->validate([
            'user_real_name' => ['required', 'string', 'max:255', Rule::unique('user_tb', 'user_real_name')->ignore($user->user_id, 'user_id')],
            'user_name' => ['required', 'string', 'max:255', Rule::unique('user_tb', 'user_name')->ignore($user->user_id, 'user_id')],
            'user_email_address' => ['nullable', 'email', 'max:100', Rule::unique('user_tb', 'user_email_address')->ignore($user->user_id, 'user_id')],
            'user_password' => ['nullable', 'string', 'min:6', 'max:20'],
        ]);
        foreach (['user_real_name', 'user_name', 'user_email_address'] as $field) {
            $user->$field = $values[$field] ?? '';
        }
        if ($request->filled('user_password')) $user->user_password = Hash::make($values['user_password']);
        $user->updated_by_user_idx = session('loginID');
        $user->save();
        $warning = $request->filled('user_password') ? $this->sendCredentials($user, $values['user_password']) : null;

        return response()->json(['success' => 'Account information updated successfully.', 'warning' => $warning]);
    }

    private function fillUser(UserAccountModel $user, array $values): void
    {
        foreach (['user_real_name', 'user_name', 'user_job_title', 'user_email_address', 'user_type', 'user_status'] as $field) {
            $user->$field = $values[$field] ?? '';
        }
        $user->user_branch_access_type = $values['user_access'];
        if (!empty($values['user_password'])) $user->user_password = Hash::make($values['user_password']);
    }

    private function summary(): array
    {
        $users = UserManagementAccess::visibleUsers();

        $summary = [
            'total' => (clone $users)->count(),
            'active' => (clone $users)->where('user_status', 'Active')->count(),
            'administrators' => (clone $users)->whereIn('user_type', ['Admin', 'SUAdmin'])->count(),
            'by_branch' => (clone $users)->where('user_branch_access_type', 'BYBRANCH')->count(),
        ];
        if (UserManagementAccess::manager()->user_type === 'SUAdmin') {
            $summary['online'] = $this->presence->onlineCount($users);
            $summary['online_window_minutes'] = (int) ceil($this->presence->windowSeconds() / 60);
        }

        return $summary;
    }

    private function sendCredentials(UserAccountModel $user, string $password, bool $created = false): ?string
    {
        if (!$user->user_email_address) return null;
        try {
            Mail::to($user->user_email_address)->send(new ResetPassword(
                'Teves Billing System Portal: ' . ($created ? 'User Account Created' : 'Password Reset'),
                $created ? 'Your user account has been created. Please use the credentials below.' : 'Your password has been changed. Please use the credentials below.',
                $user->user_real_name, $user->user_id, $user->user_name, $password
            ));
        } catch (\Throwable $exception) {
            Log::warning('User credential notification failed.', ['user_id' => $user->user_id]);

            return 'The account was saved, but the notification email could not be sent.';
        }

        return null;
    }
}
