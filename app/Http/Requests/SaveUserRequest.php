<?php

namespace App\Http\Requests;

use App\Support\UserManagementAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    public function authorize()
    {
        UserManagementAccess::manager();

        return true;
    }

    public function rules()
    {
        $updating = $this->route()->getActionMethod() === 'update_user_post';
        if ($updating) validator($this->only('userID'), ['userID' => 'required|integer'])->validate();
        // Only a database-resolved ID is passed to unique()->ignore().
        $target = $updating ? UserManagementAccess::target($this->input('userID')) : null;
        $roles = ['Admin', 'Supervisor', 'Accounting_Staff', 'Encoder'];
        if (UserManagementAccess::manager()->user_type === 'SUAdmin') {
            $roles[] = 'SUAdmin';
        }

        return [
            'userID' => [$updating ? 'required' : 'nullable', 'integer'],
            'user_real_name' => ['required', 'string', 'max:255', Rule::unique('user_tb', 'user_real_name')->ignore($target ? $target->user_id : null, 'user_id')],
            'user_job_title' => ['nullable', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', Rule::unique('user_tb', 'user_name')->ignore($target ? $target->user_id : null, 'user_id')],
            'user_email_address' => ['required', 'email', 'max:100', Rule::unique('user_tb', 'user_email_address')->ignore($target ? $target->user_id : null, 'user_id')],
            'user_password' => [$updating ? 'nullable' : 'required', 'string', 'min:6', 'max:20'],
            'user_type' => ['required', Rule::in($roles)],
            'user_status' => ['required', Rule::in(['Active', 'Inactive'])],
            'user_access' => ['required', Rule::in(['ALL', 'BYBRANCH'])],
        ];
    }

    public function attributes()
    {
        return [
            'user_real_name' => 'full name', 'user_name' => 'username',
            'user_email_address' => 'email address', 'user_password' => 'password',
            'user_type' => 'role', 'user_status' => 'status',
            'user_access' => 'branch access', 'user_job_title' => 'job title',
        ];
    }
}
