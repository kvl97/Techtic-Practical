<?php

namespace App\Http\Middleware;

use Closure,Session,Route,redirect;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
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
        $this->auth = Auth::guard('customers');
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
        if (Auth::guard('customers')->guest())
        {
            
            if ($request->ajax()) {
               
                return response('Unauthorized.', 401);
            } else {
                
                return redirect(FRONTEND_URL.'login');
            }
        } else{
            return $next($request);
        }
        return $next($request);
    }
}
