<?php

namespace App\Http\Controllers;

use App\Models\BankModel;
use App\Models\CashDeposit;
use App\Models\TevesBranchModel;
use App\Models\User;
use App\Services\CashDepositAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CashDepositPageController extends Controller
{
    public function index(Request $request, CashDepositAccess $access)
    {
        $user = $access->user();
        $query = CashDeposit::query();
        if ($user->user_type === 'Encoder') {
            $query->where('teves_cash_deposits.created_by_user_id', $user->user_id);
        }
        $summary = (clone $query)->selectRaw('COUNT(*) AS deposit_count, COALESCE(SUM(cash_deposit_amount), 0) AS total_amount')->first();
        $editableCount = (clone $query)
            ->where('created_by_user_id', $user->user_id)
            ->where('created_at', '>', now()->subHours(48))
            ->count();

        $search = substr(trim((string) $request->query('q', '')), 0, 100);
        $deposits = $query
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'teves_cash_deposits.branch_idx')
            ->leftJoin('teves_bank_table as bank', 'bank.bank_id', '=', 'teves_cash_deposits.bank_idx')
            ->leftJoin('user_tb as encoder', 'encoder.user_id', '=', 'teves_cash_deposits.created_by_user_id')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($match) use ($search) {
                    $match->where('teves_cash_deposits.cash_deposit_reference', 'like', '%' . $search . '%')
                        ->orWhere('branch.branch_code', 'like', '%' . $search . '%')
                        ->orWhere('bank.bank_name', 'like', '%' . $search . '%')
                        ->orWhere('teves_cash_deposits.cash_deposit_bank', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('teves_cash_deposits.created_at')
            ->select('teves_cash_deposits.*', 'branch.branch_code as site_branch_code', 'bank.bank_name', 'bank.bank_account_number', 'encoder.user_real_name as encoder_name')
            ->paginate(20)->withQueryString();

        $banks = BankModel::orderBy('bank_name')->get(['bank_id', 'bank_name', 'bank_account_number']);
        $branches = $this->branchesFor($user);
        $data = $user;
        $title = 'Cash Deposits';

        return view('pages.cash_deposits.index', compact('data', 'title', 'summary', 'editableCount', 'deposits', 'banks', 'branches', 'search', 'access'));
    }

    public function store(Request $request, CashDepositAccess $access)
    {
        $user = $access->user();
        $values = $this->validated($request, null, $user);
        $photo = $request->file('cash_deposit_photo')->store('cash_deposits', 'public');

        try {
            $deposit = new CashDeposit();
            $this->fillDeposit($deposit, $values);
            $deposit->created_by_user_id = $user->user_id;
            $deposit->cash_deposit_photo = $photo;
            $deposit->save();
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($photo);
            throw $exception;
        }

        return response()->json(['message' => 'Cash deposit saved.'], 201);
    }

    public function photo($depositId, CashDepositAccess $access)
    {
        $user = $access->user();
        $deposit = $access->deposit($depositId);
        $access->authorizeView($deposit, $user);

        $path = $deposit->cash_deposit_photo;
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path);
    }

    public function update($depositId, Request $request, CashDepositAccess $access)
    {
        $user = $access->user();
        $deposit = $access->deposit($depositId);
        $access->authorizeChange($deposit, $user);
        $values = $this->validated($request, $deposit, $user);
        $oldPhoto = $deposit->cash_deposit_photo;
        $newPhoto = $request->hasFile('cash_deposit_photo')
            ? $request->file('cash_deposit_photo')->store('cash_deposits', 'public') : null;

        try {
            $this->fillDeposit($deposit, $values);
            $deposit->updated_by_user_id = $user->user_id;
            if ($newPhoto) $deposit->cash_deposit_photo = $newPhoto;
            $deposit->save();
        } catch (\Throwable $exception) {
            if ($newPhoto) Storage::disk('public')->delete($newPhoto);
            throw $exception;
        }
        if ($newPhoto && $oldPhoto) Storage::disk('public')->delete($oldPhoto);

        return response()->json(['message' => 'Cash deposit updated.']);
    }

    public function destroy($depositId, CashDepositAccess $access)
    {
        $user = $access->user();
        $deposit = $access->deposit($depositId);
        $access->authorizeChange($deposit, $user);
        $deposit->deleted_by_user_id = $user->user_id;
        $deposit->save();
        $deposit->delete();

        return response()->json(['message' => 'Cash deposit deleted.']);
    }

    private function validated(Request $request, ?CashDeposit $deposit, User $user): array
    {
        $branchIds = $this->branchesFor($user)->pluck('branch_id')->all();

        return $request->validate([
            'branch_idx' => ['required', 'integer', Rule::in($branchIds)],
            'bank_idx' => ['required', 'integer', Rule::exists('teves_bank_table', 'bank_id')->whereNull('deleted_at')],
            'cash_deposit_date' => ['required', 'date_format:Y-m-d'],
            'cash_deposit_amount' => ['required', 'numeric', 'gt:0'],
            'cash_deposit_reference' => [
                'required', 'string', 'max:255',
                Rule::unique('teves_cash_deposits', 'cash_deposit_reference')
                    ->whereNull('deleted_at')
                    ->ignore($deposit ? $deposit->cash_deposit_id : null, 'cash_deposit_id'),
            ],
            'cash_deposit_remarks' => ['nullable', 'string', 'max:5000'],
            'cash_deposit_photo' => [$deposit ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png', 'max:10048'],
        ]);
    }

    private function fillDeposit(CashDeposit $deposit, array $values): void
    {
        foreach (['branch_idx', 'bank_idx', 'cash_deposit_date', 'cash_deposit_amount', 'cash_deposit_reference', 'cash_deposit_remarks'] as $field) {
            $deposit->{$field} = $values[$field] ?? null;
        }
    }

    private function branchesFor(User $user)
    {
        return TevesBranchModel::query()
            ->when($user->user_branch_access_type !== 'ALL', function ($query) use ($user) {
                $query->whereIn('branch_id', DB::table('teves_user_branch_access')
                    ->where('user_idx', $user->user_id)->select('branch_idx'));
            })
            ->orderBy('branch_code')->get(['branch_id', 'branch_code', 'branch_name']);
    }
}
