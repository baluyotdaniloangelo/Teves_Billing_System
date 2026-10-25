<?php

namespace App\Http\Controllers;

use App\Services\UserAccessService;
use Illuminate\Http\Request;

class UserProductAccessController extends Controller
{
    public function user_product_access(Request $request, UserAccessService $access)
    {
        return $access->listing($request, 'category');
    }

    public function add_user_product_access_post(Request $request, UserAccessService $access)
    {
        return $access->save($request, 'category');
    }
}
