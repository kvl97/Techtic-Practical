<?php

namespace App\Http\Middleware;

use Closure,Session,Route,redirect;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
        $this->auth = Auth::guard('admin');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /***
            To check current session value match with current page added $para and else part
        */
        $para = Route::current()->parameters();
        
        if (Auth::guard('admin')->guest())
        {
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                
                return redirect(ADMIN_URL.'login');
            }
        } else{
            return $next($request);
        }
        return $next($request);
    }
}
