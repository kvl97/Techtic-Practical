<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure,Session,Route,redirect;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Permission
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard('admin');
    }

    public function handle($request, Closure $next, $access_id, $action)
    {
	    if(!Auth::guard('admin')->user()->hasPermission($access_id, $action)){
            return redirect(ADMIN_URL.'dashboard');
        }
		return $next($request);
    }



}
