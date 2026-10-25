<?php

namespace App\Services;

use App\Models\BranchModel;
use App\Models\ProductCategoryModel;
use App\Models\UserAccountModel;
use App\Support\UserManagementAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAccessService
{
    private function configuration(string $type): array
    {
        return $type === 'branch'
            ? [new BranchModel(), 'teves_user_branch_access', 'branch_idx', 'site_items', ['branch_id', 'branch_code', 'branch_name']]
            : [new ProductCategoryModel(), 'teves_user_product_category_access', 'category_idx', 'category_items', ['category_id', 'category_name']];
    }

    public function listing(Request $request, string $type)
    {
        UserManagementAccess::manager();
        $request->validate(['UserID' => 'required|integer']);
        $user = UserManagementAccess::target($request->UserID);
        [$model, $table, $column, $input, $fields] = $this->configuration($type);
        $selected = DB::table($table)->where('user_idx', $user->user_id)->pluck($column)->map(function ($id) { return (int) $id; })->all();
        $rows = $model->newQuery()->orderBy($fields[1])->get($fields)->map(function ($row, $index) use ($fields, $selected) {
            $id = (int) $row->{$fields[0]};

            return array_merge($row->toArray(), ['DT_RowIndex' => $index + 1, 'selected' => in_array($id, $selected, true)]);
        });

        return response()->json(['data' => $rows, 'user' => $user->only(['user_id', 'user_real_name', 'user_name', 'user_type', 'user_branch_access_type'])]);
    }

    public function save(Request $request, string $type)
    {
        UserManagementAccess::manager();
        $request->validate(['userID' => 'required|integer']);
        $user = UserManagementAccess::target($request->userID);
        [$model, $table, $column, $input] = $this->configuration($type);
        $request->validate([$input => ['present']]);
        // Accept arrays and the comma-separated payload used by the original page.
        $items = $request->input($input) ?? [];
        if (is_string($items)) $items = $items === '' ? [] : explode(',', $items);
        $request->merge([$input => $items]);
        $values = $request->validate([
            $input => ['present', 'array', 'max:10000'],
            $input . '.*' => ['required', 'integer', 'distinct', Rule::exists($model->getTable(), $model->getKeyName())->whereNull('deleted_at')],
        ]);
        DB::transaction(function () use ($user, $table, $column, $values, $input) {
            UserAccountModel::whereKey($user->user_id)->lockForUpdate()->firstOrFail();
            $before = DB::table($table)->where('user_idx', $user->user_id)->pluck($column)->map(function ($id) { return (int) $id; })->sort()->values()->all();
            DB::table($table)->where('user_idx', $user->user_id)->delete();
            $rows = array_map(function ($id) use ($user, $column) {
                return ['user_idx' => $user->user_id, $column => (int) $id, 'created_by_user_idx' => session('loginID'), 'created_at' => now(), 'updated_at' => now()];
            }, $values[$input]);
            foreach (array_chunk($rows, 100) as $chunk) DB::table($table)->insert($chunk);
            $after = array_map('intval', $values[$input]); sort($after);
            if ($before !== $after) {
                $field = $column === 'branch_idx' ? 'branch_ids' : 'category_ids';
                app(UserActivityService::class)->record($column === 'branch_idx' ? 'User branch access' : 'User product access', 'updated', [
                    'old' => [$field => $before], 'attributes' => [$field => $after],
                ], $user);
            }
        });

        return response()->json(['success' => ($type === 'branch' ? 'Branch' : 'Product category') . ' access saved successfully.']);
    }
}
