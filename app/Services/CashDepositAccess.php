<?php

namespace App\Services;

use App\Models\CashDeposit;
use App\Models\User;

class CashDepositAccess
{
    public const ROLES = ['SUAdmin', 'Admin', 'Accounting_Staff', 'Encoder'];

    public function user(): User
    {
        $user = User::where('user_id', session('loginID'))->firstOrFail();
        abort_unless(in_array($user->user_type, self::ROLES, true), 403);

        return $user;
    }

    public function deposit($id): CashDeposit
    {
        return CashDeposit::findOrFail($id);
    }

    public function authorizeView(CashDeposit $deposit, User $user): void
    {
        if ($user->user_type === 'Encoder') {
            abort_unless((int) $deposit->created_by_user_id === (int) $user->user_id, 403);
        }
    }

    public function canChange(CashDeposit $deposit, User $user): bool
    {
        return (int) $deposit->created_by_user_id === (int) $user->user_id
            && $deposit->created_at !== null
            && now()->lt($deposit->created_at->copy()->addHours(48));
    }

    public function authorizeChange(CashDeposit $deposit, User $user): void
    {
        abort_unless($this->canChange($deposit, $user), 403,
            'Only the encoder can edit or delete this deposit within 48 hours.');
    }
}
