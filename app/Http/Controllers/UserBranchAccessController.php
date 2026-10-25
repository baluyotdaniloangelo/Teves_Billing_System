<?php

namespace App\Http\Controllers;

use App\Services\UserAccessService;
use Illuminate\Http\Request;

class UserBranchAccessController extends Controller
{
    public function getUserBranchAccess(Request $request, UserAccessService $access)
    {
        return $access->listing($request, 'branch');
    }

    public function add_user_access_post(Request $request, UserAccessService $access)
    {
        return $access->save($request, 'branch');
    }
}
