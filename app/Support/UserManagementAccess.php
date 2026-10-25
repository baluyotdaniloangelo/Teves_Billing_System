<?php

namespace App\Support;

use App\Models\UserAccountModel;

class UserManagementAccess
{
    public static function actor(): UserAccountModel
    {
        $actor = UserAccountModel::find(session('loginID'));
        abort_unless($actor && $actor->user_status === 'Active', 403, 'Your account does not have access.');

        return $actor;
    }

    public static function manager(): UserAccountModel
    {
        $actor = static::actor();
        abort_unless(in_array($actor->user_type, ['Admin', 'SUAdmin'], true), 403, 'Administrator access is required.');

        return $actor;
    }

    public static function visibleUsers()
    {
        $actor = static::manager();

        return UserAccountModel::query()->when($actor->user_type !== 'SUAdmin', function ($query) {
            $query->where('user_type', '<>', 'SUAdmin');
        });
    }

    public static function target($id): UserAccountModel
    {
        return static::visibleUsers()->findOrFail($id);
    }
}
