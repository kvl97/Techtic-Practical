<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class ContactEnquiryController extends BaseController {

    public function getIndex() {
        return View('backend.contact_enquiry.index', array('title' => "Contact Enquiry"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_firstname','v_email','v_phone','t_message');
        $query = new ContactUs;

        if (isset($data['v_firstname']) && trim($data['v_firstname']) != '') {
            $query = $query->where(DB::raw("CONCAT(v_firstname, ' ',v_lastname)"), 'LIKE', '%' . trim($data['v_firstname']) . '%');
        }
        if (isset($data['v_email']) && $data['v_email'] != '') {
            $query = $query->where('v_email', 'LIKE', '%' . $data['v_email'] . '%');
        }
        if (isset($data['v_phone']) && $data['v_phone'] != '') {
            $query = $query->where('v_phone', 'LIKE', '%' . $data['v_phone'] . '%');
        }
        if (isset($data['t_message']) && $data['t_message'] != '') {
            $query = $query->where('t_message', 'LIKE', '%' . $data['t_message'] . '%');
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
            $query = $query->orderBy($order_field, $sort_order);
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            if(isset($this->permission) && isset($this->permission[16]['i_delete']) && $this->permission[16]['i_delete'] == 1) {
                
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_firstname'].' '.$val['v_lastname'];
            $data[$key][$index++] = $val['v_email'];
            $data[$key][$index++] = $val['v_phone'];
            $data[$key][$index++] = $val['t_message'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[16]['i_delete']) && $this->permission[16]['i_delete'] == 1) {

               $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'contact-enquiry/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
            }
            $action .= '</div>';
            $data[$key][$index++] = $action;
        }
        $return_data['data'] = $data;
        $return_data['iTotalRecords'] = $arrUsers['total'];
        $return_data['iTotalDisplayRecords'] = $arrUsers['total'];
        $return_data['data_array'] = $arrUsers['data'];
        return $return_data;
    }

    public function getDelete($id) {
        $record = ContactUs::find($id);
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
                $user_data = ContactUs::whereIn('id', array_values($data['ids']))->get();
                if ($user_data) {
                    foreach ($user_data as $data) {

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

