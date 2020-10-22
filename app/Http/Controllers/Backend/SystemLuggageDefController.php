<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemLuggageDef;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class SystemLuggageDefController extends BaseController {

    public function getIndex() {
        return View('backend.system_luggage_def.index', array('title' => "Luggage"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_name','e_type','d_unit_price','e_is_free');
        $query = new SystemLuggageDef;

        if (isset($data['v_name']) && $data['v_name'] != '') {
            $query = $query->where('v_name', 'LIKE', '%' . $data['v_name'] . '%');
        }
        if (isset($data['e_type']) && $data['e_type'] != '') {
            $query = $query->where('e_type', 'LIKE', '%' . $data['e_type'] . '%');
        }
        if (isset($data['d_unit_price']) && $data['d_unit_price'] != '') {
            $query = $query->where('d_unit_price', 'LIKE', '%' . $data['d_unit_price'] . '%');
        }
        if (isset($data['e_is_free']) && $data['e_is_free'] != '') {
            $query = $query->where('e_is_free', 'LIKE', '%' . $data['e_is_free'] . '%');
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
            if(isset($this->permission) && isset($this->permission[8]['i_delete']) && $this->permission[8]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_name'];
            $data[$key][$index++] = $val['e_type'];
            $data[$key][$index++] = $val['d_unit_price'];
            $data[$key][$index++] = $val['e_is_free'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[8]['i_add_edit']) && $this->permission[8]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'system-luggage-def/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) && isset($this->permission[8]['i_delete']) && $this->permission[8]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'system-luggage-def/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
            $record = new SystemLuggageDef;
            $validator = Validator::make($inputs, [
                    'v_name' => 'required',
                    'e_type' => 'required',
                    // 'd_unit_price'=> 'required',
                    'e_is_free' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->v_name = trim($inputs['v_name']);
                $record->e_type = trim($inputs['e_type']);
                $record->d_unit_price = isset($inputs['d_unit_price']) ? trim($inputs['d_unit_price']) : 0 ;
                $record->e_is_free = trim($inputs['e_is_free']);
                $record->v_desc = trim($inputs['v_desc']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'System luggage definition added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.system_luggage_def.add', array('title' => 'Add Label'));
        }
        return Redirect(ADMIN_URL . 'system_luggage_def');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        // pr($inputs); exit;
        $record = SystemLuggageDef::find($id);
        if ($record || !empty($record)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'v_name' => 'required',
                    'e_type' => 'required',
                    // 'd_unit_price'=> 'required',
                    'e_is_free' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_name = trim($inputs['v_name']);
                    $record->e_type = trim($inputs['e_type']);
                    if($inputs['e_is_free'] == 'Yes') {
                        $record->d_unit_price =  0 ;
                    } else {
                        $record->d_unit_price = trim($inputs['d_unit_price']);
                    }
                    $record->v_desc = trim($inputs['v_desc']);
                    $record->e_is_free = trim($inputs['e_is_free']);
                    if ($record->save()) {
                        Session::flash('success-message', 'System luggage definition edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.system_luggage_def.edit', array('record' => $record, 'title' => 'Edit Label'));
            }
        }
        return Redirect(ADMIN_URL . 'system_luggage_def');
    }

    public function getDelete($id) {
        $record = SystemLuggageDef::find($id);
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
                $user_data = SystemLuggageDef::whereIn('id', array_values($data['ids']))->get();
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

