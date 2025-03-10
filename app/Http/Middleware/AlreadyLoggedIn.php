<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AlreadyLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		if(Session()->has('loginID'))
		//if(Session()->has('loginID') && (url('/')==$request->url()))
		{
			//return redirect('login')->with('fail', "You Have to Login First");
			//return back();
			return redirect('billing');
		}
        return $next($request);
    }
}
