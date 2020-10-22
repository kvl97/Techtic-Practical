<?php

namespace App\Http\Middleware;

use Closure,Session,Route,redirect;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservations;

class VerifyIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $param = $request->route()->parameters();
        
        if(isset($param['id']) && $param['id'] != '') {
            // To not allow non admin users to edit  past date reservations condition
            $reservation_record = Reservations::where(['id' => $param['id']])->first();
            if(!Auth::guard('admin')->check() && $reservation_record && $reservation_record->d_travel_date < date('Y-m-d')) {
                return redirect(FRONTEND_URL.'book-a-shuttle');
            }

            if (!Auth::guard('admin')->check() && !Auth::guard('customers')->check()) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'TRUE',
                        'redirect_url' => SITE_URL.'login',
                       
                    ]); 
                } else {
                    return redirect(SITE_URL.'login');
                }
                
            } else if(Auth::guard('customers')->check()) {
                $customer_id = auth()->guard('customers')->user()->id;
                $reservation_record = Reservations::where(['i_customer_id' => $customer_id, 'id' => $param['id']])->first();
                if(empty($reservation_record)) {
                    return redirect(SITE_URL.'upcoming-reservation');
                }
            }
        }
        return $next($request);
    }
}