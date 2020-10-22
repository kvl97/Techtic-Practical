<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlackoutDate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class BlackoutDateController extends BaseController {

    public function getIndex() {
        return View('backend.blackout_date.index', array('title' => "Date Notices"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','d_blackout_date','v_date_desc','e_status');
        $query = new BlackoutDate;

        if (isset($data['date_from']) && trim($data['date_from']) != '') {
            $query = $query->where(DB::raw('DATE(d_blackout_date)'), '>=', trim(date('Y-m-d',strtotime($data['date_from']))));
        }
        if (isset($data['date_to']) && trim($data['date_to']) != '') {
            $query = $query->where(DB::raw('DATE(d_blackout_date)'), '<=', trim(date('Y-m-d', strtotime($data['date_to']))) );
        }
        if (isset($data['v_date_desc']) && $data['v_date_desc'] != '') {
            $query = $query->where('v_date_desc', 'LIKE', '%' . $data['v_date_desc'] . '%');
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status',$data['e_status']);
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
            if(isset($this->permission) && isset($this->permission[11]['i_delete']) && $this->permission[11]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] =  date(DATE_FORMAT,strtotime($val['d_blackout_date']));
            $data[$key][$index++] = $val['v_date_desc'];
            $data[$key][$index++] = $val['e_status'];
            

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[11]['i_add_edit']) && $this->permission[11]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'system-blackout-date/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) && isset($this->permission[11]['i_delete'])  &&$this->permission[11]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'system-blackout-date/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
        if ($inputs) {
            $record = new BlackoutDate;
            $validator = Validator::make($inputs, [
                'd_blackout_date' => 'required',
                'v_date_desc' => 'required',
                'e_status' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {

                $record->d_blackout_date = date(SAVE_DATE_FORMAT, strtotime(str_replace('/', '-', $inputs['d_blackout_date'])));
                $record->v_date_desc = trim($inputs['v_date_desc']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();

                if ($record->save()) {
                    Session::flash('success-message', 'Date Notices added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.blackout_date.add', array('title' => 'Add Date Notices'));
        }
        return Redirect(ADMIN_URL . 'system-blackout-date');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $records  = BlackoutDate::find($id);
        if ($records || !empty($records)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'd_blackout_date' => 'required',
                    'v_date_desc' => 'required',
                    'e_status' => 'required',
                ]);
                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {

                    $records->d_blackout_date = date(SAVE_DATE_FORMAT, strtotime($inputs['d_blackout_date']));
                    $records->v_date_desc = trim($inputs['v_date_desc']);
                    $records->e_status = trim($inputs['e_status']);
                    if ($records->save()) {
                        Session::flash('success-message', 'Date Notices edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.blackout_date.edit', array('records' => $records , 'title' => 'Edit Date Notices'));
            }
        }
        return Redirect(ADMIN_URL . 'system-blackout-date');
    }

    public function getDelete($id) {
        $record = BlackoutDate::find($id);
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
                $user_data = BlackoutDate::whereIn('id', array_values($data['ids']))->get();
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

