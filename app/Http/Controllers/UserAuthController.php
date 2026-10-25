<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Hash;
use Session;
use Validator;

class UserAuthController extends Controller
{
    public function login(){
        return view("auth.login");
    }

	public function passwordreset(){
		$title = 'Reset Password';
        return view("auth.reset", compact('title'));
    }
	
    public function loginUser(Request $request){ 
		
		$request->validate([
            'user_name'=>'required|min:1|max:12', 
            'InputPassword'=>'required|min:6|max:20'
        ]);
		
		$user = User::where('user_name', '=', $request->user_name)->whereNull('deleted_at')->first();

		if ($user) {
			// Check if account is active
			if ($user->user_status !== 'Active') {
				return back()->with('fail', 'Your account is inactive. Please contact the administrator.');
			}

			// Check password
			if (Hash::check($request->InputPassword, $user->user_password)) {

				$request->session()->regenerate();
				$request->session()->put('loginID', $user->user_id);
				$request->session()->put('UserType', $user->user_type);
				$request->session()->put('user_branch_access_type', $user->user_branch_access_type);
				app(\App\Services\UserPresenceService::class)->touch($request);
				app(\App\Services\UserActivityService::class)->record('Authentication', 'login');

				// Redirect based on user type
				if ($user->user_type !== 'SUAdmin') {
					return redirect('billing');
				}

				return redirect('dashboard');

			} else {
				return back()->with('fail', 'Incorrect Password');
			}
		} else {
			return back()->with('fail', 'This Username is not Registered.');
		}

    }

    public function logout(Request $request){
        app(\App\Services\UserActivityService::class)->record('Authentication', 'logout');
        app(\App\Services\UserPresenceService::class)->logout($request);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
