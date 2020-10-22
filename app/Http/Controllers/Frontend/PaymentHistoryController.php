<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions;
use App\Models\Reservations;
use App\Models\Customers;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash, View;

class PaymentHistoryController extends BaseController {

    public function getPaymentHistory() {
        $current_user = auth()->guard('customers')->user();
        $user = auth()->guard('customers')->user();
        
        return view('frontend.payment_history.index', array('title' => "Payment History", 'users' => $current_user));
    }

    public function paymentHistoryListAjax(Request $request){
        $current_user = auth()->guard('customers')->user();
        $today = date("Y-m-d", strtotime(date('Y-m-d')));
        $data = $request->all();
        DB::enableQueryLog();

        $query = Transactions::has('Reservation')->with(['Reservation' => function($q) {
            $q->select('reservations.id', 'reservations.v_reservation_number', 'reservations.d_travel_date');
        }])->where('i_customer_id', $current_user->id);            
       
        if(isset($data['past_reserv_data']) && $data['past_reserv_data'] != '') {
            
            if(strtolower($data['past_reserv_data']) == 'booking') {
                $type = 'Booked';
                // $query =  $query->orWhere('e_type', 'LIKE', '%' . $type . '%');
                  
            } else if(strtolower($data['past_reserv_data']) == 'refund') {
                $type = 'Refunded';
                // $query =  $query->orWhere('e_type', 'LIKE', '%' . $type . '%');
            } else {
                $type = $data['past_reserv_data'];
            }

            $query = $query->where(function($q) use($data, $type) {
                        $q->orWhereHas('Reservation', function($q) use($data){
                            $q->where('reservations.v_reservation_number', 'LIKE', '%' . $data['past_reserv_data'] . '%');
                            $q->orWhere(DB::raw('DATE(reservations.d_travel_date)'), '=', date('Y-m-d', strtotime(str_replace('/', '-', $data['past_reserv_data']))));
                        })->orWhere('d_amount', 'LIKE', '%' . $data['past_reserv_data'] . '%')
                        ->orWhere('e_status', 'LIKE', '%' . $data['past_reserv_data'] . '%')
                        ->orWhere('e_type', 'LIKE', '%' . $type . '%');
                    });
  
        } 
        $query = $query->orderBy('updated_at', 'desc');
        $rec_per_page = REC_PER_PAGE;
        
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $queries = DB::getQueryLog();
        
        /* pr($arrUsers);
        exit; */

        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            $upcoming_data = Reservations::where('i_parent_id', NULL)->where('i_customer_id',$current_user->id)->where('d_travel_date','>=', $today)->where('id', $val['i_reservation_id'])->get()->count();
            if($upcoming_data != 0) {
                $reservation_number = '<a href="'.FRONTEND_URL.'payment-upcoming-reservation/'.$val['i_reservation_id'].'" rel="'.$val['id'].'">'.$val['reservation']['v_reservation_number'].'</a>';
            } else {
                $reservation_number = '<a href="'.FRONTEND_URL.'payment-past-reservation/'.$val['i_reservation_id'].'" rel="'.$val['id'].'">'.$val['reservation']['v_reservation_number'].'</a>';
            }
            $data[$key][$index++] = $reservation_number;
            $data[$key][$index++] = date(DATETIME_FORMAT, strtotime($val['reservation']['d_travel_date']));
            $data[$key][$index++] = '<span style= "float: right;">$'.$val['d_amount'].'</span>';

            // $data[$key][$index++] = ($val['e_type'] == 'Booked') ? 'Booking' : '<span class= "type_refund_color">Refund</span>';
            $type_val = '';
            if($val['e_type'] == 'Booked-Wallet') {
                $type_val .= 'Booking';
                $type_val .= ' <i class="fa fa-info-circle tooltip_error_log" title="Booked via wallet money" aria-hidden="true" data-toggle="tooltip" rel="tooltip"></i>';
            } else {
                $type_val .= ($val['e_type'] == 'Booked') ? 'Booking' : '<span class= "type_refund_color">Refund</span>';
            }
            $data[$key][$index++] = $type_val;

            if(isset($val['v_error_log']) && $val['v_error_log'] != '' && $val['v_error_log'] != NULL) {
                $status_val = '<span class="status_failed_color">';
                $status_val .= $val['e_status'];
                $status_val .= ' <i class="fa fa-info-circle" title="'.$val['v_error_log'].' "aria-hidden="true" data-toggle="tooltip" rel="tooltip" data-placement="left"></i>';
                $status_val .= '</span>';
            } else { 
                $status_val = '<span class="status_success_color">';
                $status_val .= $val['e_status'];
                $status_val .= '</span>';
            } 
            $data[$key][$index++] = $status_val;
            
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

}