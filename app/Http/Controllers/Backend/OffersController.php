<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Offers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class OffersController extends BaseController {

    public function getIndex() {
        return View('backend.offers.index', array('title' => "Offers"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_coupon_code', 'f_discount_percentage', 'd_discount_flat_price', 'd_start_date', 'd_expire_date', 'e_type','e_status');
        $query = new Offers;

        if (isset($data['v_coupon_code']) && $data['v_coupon_code'] != '') {
            $query = $query->where('v_coupon_code', 'LIKE', '%' . $data['v_coupon_code'] . '%');
        }
        /* if (isset($data['v_notes']) && $data['v_notes'] != '') {
            $query = $query->where('v_notes', 'LIKE', '%' . $data['v_notes'] . '%');
        } */
        if (isset($data['f_discount_percentage']) && $data['f_discount_percentage'] != '') {
            $query = $query->where('f_discount_percentage', '=', $data['f_discount_percentage']);
        }
        if (isset($data['d_discount_flat_price']) && $data['d_discount_flat_price'] != '') {
            $query = $query->where('d_discount_flat_price', '=',  $data['d_discount_flat_price']);
        }

        if (isset($data['dStartDate']) && trim($data['dStartDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_start_date)'), '>=', trim(date('Y-m-d',strtotime( $data['dStartDate']))));
        }
        if (isset($data['dEndDate']) && trim($data['dEndDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_start_date)'), '<=', trim(date('Y-m-d', strtotime( $data['dEndDate'])))) ;
        }

        if (isset($data['dFromDate']) && trim($data['dFromDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_expire_date)'), '>=', trim(date('Y-m-d',strtotime( $data['dFromDate']))));
        }
        if (isset($data['dToDate']) && trim($data['dToDate']) != '') {
            $query = $query->where(DB::raw('DATE(d_expire_date)'), '<=', trim(date('Y-m-d', strtotime( $data['dToDate'])))) ;
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status', "=", $data['e_status']);
        }
        if (isset($data['e_type']) && $data['e_type'] != '') {
            $query = $query->where('e_type', 'LIKE', '%' . $data['e_type'] . '%');
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
            // $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--tick kt-checkbox--success"><input type="checkbox" name="id[]" value="' . $val['id'] . '"> <span></span></label>';
            if(isset($this->permission) && isset($this->permission[5]['i_delete']) && $this->permission[5]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_coupon_code'];
            /* $data[$key][$index++] = $val['v_notes']; */
            $data[$key][$index++] = isset($val['f_discount_percentage']) ? $val['f_discount_percentage']."%" : '-';
            $data[$key][$index++] = isset($val['d_discount_flat_price']) ? $val['d_discount_flat_price'] : '-';
            $data[$key][$index++] = isset($val['d_start_date']) ?  date(DATE_FORMAT,strtotime($val['d_start_date'])) : '-';
            $data[$key][$index++] = isset($val['d_expire_date']) ?  date(DATE_FORMAT,strtotime($val['d_expire_date'])) : '-';
            $data[$key][$index++] = $val['e_type'];
            $data[$key][$index++] = $val['e_status'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[5]['i_add_edit']) && $this->permission[5]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'offers/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) &&  isset($this->permission[5]['i_delete']) && $this->permission[5]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'offers/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

    public function anyAdd(Request $request) {

       $inputs = $request->all();
        // pr($inputs); exit;
        if ($inputs) {
            $record = new Offers;
            $validator = Validator::make($inputs, [
                'v_coupon_code' => [
                        'required',
                            Rule::unique('offers','v_coupon_code'),
                        ],
                'v_notes' => 'required',
                'v_usage' => 'required',
                'e_status' => 'required',
                'e_type' => 'required',
                /*'d_start_date' => 'required',
                'd_expire_date' => 'required',  */
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                // pr($inputs['d_start_date']); exit;
                $record->v_coupon_code = trim($inputs['v_coupon_code']);
                $record->v_usage = trim($inputs['v_usage']);
                $record->v_notes = trim($inputs['v_notes']);
                $record->v_max_number_of_usage = trim($inputs['v_max_number_of_usage'] ? $inputs['v_max_number_of_usage']  : NULL);
                $record->e_status = trim($inputs['e_status']);
                $record->e_trip_type = trim($inputs['e_trip_type']);
                $record->e_type = trim($inputs['e_type']);
                /* if($inputs['f_discount_percentage']) */
                if($inputs['discount'] == 'Percentage') {
                    $record->f_discount_percentage = isset($inputs['discount_value']) ? trim($inputs['discount_value']) : NULL;
                } else {
                    $record->d_discount_flat_price = isset($inputs['discount_value']) ? trim($inputs['discount_value']) : NULL;
                }

                $record->d_start_date = isset($inputs['d_start_date']) ? $record->d_start_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_start_date'])) : NULL;
               
               
                $record->d_expire_date = isset($inputs['d_expire_date']) ? date(SAVE_DATE_FORMAT, strtotime($inputs['d_expire_date'])) : NULL;
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Offer added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.offers.add', array('title' => 'Add Offer'));
        }
        return Redirect(ADMIN_URL . 'offers');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = Offers::find($id);

        if ($record || !empty($record)) {
            if ($inputs) {

                $validator = Validator::make($request->all(), [
                    'v_coupon_code' => [
                        'required',
                            Rule::unique('offers','v_coupon_code')->ignore($record->id, 'id'),
                        ],
                    'v_notes' => 'required',
                    'v_usage' => 'required',
                    'e_type' => 'required',
                    /*'d_discount_flat_price' => 'required',
                    'd_start_date' => 'required',
                    'd_expire_date' => 'required', */
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_coupon_code = trim($inputs['v_coupon_code']);
                    $record->v_notes = trim($inputs['v_notes']);
                    $record->v_usage = trim($inputs['v_usage']);
                    $record->v_max_number_of_usage = trim($inputs['v_max_number_of_usage'] ? $inputs['v_max_number_of_usage']  : NULL);

                    if(date('Y-m-d', strtotime(trim($inputs['d_expire_date']))) < date("Y-m-d") && $inputs['e_status'] != 'Expired' && $inputs['d_expire_date'] != '') {
                        return 'PREVENT_EXPIRE_STATUS';
                    } else {
                        $record->e_status = trim($inputs['e_status']);
                    }

                    $record->e_trip_type = trim($inputs['e_trip_type']);
                    $record->e_type = trim($inputs['e_type']);
                    if($inputs['discount'] == 'Percentage') {
                        $record->f_discount_percentage = isset($inputs['discount_value']) ? trim($inputs['discount_value']) : NULL;
                        $record->d_discount_flat_price = NULL;
                    } else {
                        $record->f_discount_percentage = NULL;
                        $record->d_discount_flat_price = isset($inputs['discount_value']) ? trim($inputs['discount_value']) : NULL;
                    }
                    $record->d_start_date = isset($inputs['d_start_date']) ? date(SAVE_DATE_FORMAT, strtotime($inputs['d_start_date'])) : NULL;

                    $record->d_expire_date = isset($inputs['d_expire_date']) ? date(SAVE_DATE_FORMAT, strtotime($inputs['d_expire_date'])) : NULL;

                    if ($record->save()) {
                        Session::flash('success-message', 'Offer updated successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.offers.edit', array('record' => $record, 'title' => 'Edit Offer'));
            }
        }
        return Redirect(ADMIN_URL . 'offers');
    }

    public function getDelete($id) {
        $record = Offers::find($id);
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
                $user_data = Offers::whereIn('id', array_values($data['ids']))->get();
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
