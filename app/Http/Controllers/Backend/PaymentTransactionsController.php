<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class PaymentTransactionsController extends BaseController {

    public function getIndex() {
        return View('backend.payment_transactions.index', array('title' => "Payment Transactions"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('v_customer_name', 'v_reservation_number', 'transactions.created_at', 'v_stripe_payment_id', 'd_amount', 'e_type', 'transactions.e_status');
        $query = new Transactions;
        $query = $query->select('transactions.*','r.v_reservation_number',DB::raw('CONCAT(v_firstname," ",v_lastname) as v_customer_name'));
        $query = $query->leftJoin('reservations as r','r.id','=','transactions.i_reservation_id');
        $query = $query->leftJoin('customers as c','c.id','=','transactions.i_customer_id');

        if (isset($data['v_customer_name']) && $data['v_customer_name'] != '') {
            $query = $query->where(DB::raw('CONCAT(v_firstname," ",v_lastname)'), 'LIKE', '%' . $data['v_customer_name'] . '%');
        }
        if (isset($data['v_reservation_number']) && $data['v_reservation_number'] != '') {
            $query = $query->where('v_reservation_number', '=', $data['v_reservation_number']);
        }
        if (isset($data['v_stripe_payment_id']) && $data['v_stripe_payment_id'] != '') {
            $query = $query->where('v_stripe_payment_id', 'LIKE', '%' . $data['v_stripe_payment_id'] . '%');
        }
        if (isset($data['d_amount']) && $data['d_amount'] != '') {
            $query = $query->where('d_amount', '=', $data['d_amount']);
        }
        if (isset($data['e_type']) && $data['e_type'] != '') {
            $query = $query->where('e_type', $data['e_type']);
        }
        if (isset($data['d_start_date']) && trim($data['d_start_date']) != '') {
            $query = $query->where(DB::raw('DATE(transactions.created_at)'), '>=', trim(date('Y-m-d',strtotime(trim($data['d_start_date'])))));
        }
        if (isset($data['d_end_date']) && trim($data['d_end_date']) != '') {
            $query = $query->where(DB::raw('DATE(transactions.created_at)'), '<=', trim(date('Y-m-d', strtotime(trim($data['d_end_date'])))));
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('transactions.e_status',$data['e_status']);
        }

        $rec_per_page = REC_PER_PAGE;
        if (isset($data['length'])) {
            if ($data['length'] == '-1') {
                $rec_per_page = '';
            } else {
                $rec_per_page = $data['length'];
            }
        }

        $sort_order = $data['order']['0']['dir'];
       
        $order_field = $sortColumn[$data['order']['0']['column']];
        
        if ($sort_order != '' && $order_field != '') {
            if($data['order']['0']['column'] == 2 && $sort_order == 'desc') {
                $query = $query->orderBy('transactions.created_at', 'desc');
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
                
        } else {
            $query = $query->orderBy('transactions.created_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
       
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            
            $data[$key][$index++] = '<a href="' . ADMIN_URL .'customers/edit/'.$val['i_customer_id']. '">'.$val['v_customer_name'].'</a>';
            $data[$key][$index++] = '<a href="' . ADMIN_URL .'reservations/view/'.$val['i_reservation_id']. '">'.$val['v_reservation_number'].'</a>';
            $data[$key][$index++] = $val['created_at'] ? date('m/d/Y h:i A',strtotime($val['created_at'])) : '';
            $data[$key][$index++] = $val['v_stripe_payment_id'];
            $data[$key][$index++] = $val['d_amount'] ?  '<span style="float: right;">$'.$val['d_amount'].'</span>' : '';

            $type_val = '';
            if($val['e_type'] == 'Booked-Wallet') {
                $type_val .= 'Booking';
                $type_val .= ' <i class="fa fa-info-circle tooltip_error_log" title="Booked via wallet money" aria-hidden="true" data-toggle="tooltip" rel="tooltip" data-placement="left"></i>';
            } else {
                $type_val .= $val['e_type'];
            }
            $data[$key][$index++] = $type_val;

            $status_log = '';
            if(isset($val['v_error_log']) && $val['v_error_log'] != '' && $val['v_error_log'] != NULL) { 
                $status_log .= $val['e_status'];
                $status_log .= ' <i class="fa fa-info-circle tooltip_error_log" title="'.$val['v_error_log'].'" aria-hidden="true" data-toggle="tooltip" rel="tooltip" data-placement="left"></i>';
            } else {
                $status_log .= $val['e_status'];
            } 

            $data[$key][$index++] = $status_log;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function getDelete($id) {
        $record = Transactions::find($id);
        if (!empty($record)) {
            if ($record->delete()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        } else {
            return 'FALSE';
        }
    }

    public function postBulkAction(Request $request) {
        $data = $request->all();
        if (count($data) > 0) {
            if ($data['action'] == 'Delete') {
                $linerun_data = Transactions::whereIn('id', array_values($data['ids']))->get();
                if ($linerun_data) {
                    foreach ($linerun_data as $data) {

                        $data->deleted_at = Carbon::now();
                        $data->save();
                        $data->delete();
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }
}
?>
