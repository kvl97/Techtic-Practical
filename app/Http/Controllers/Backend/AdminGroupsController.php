<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminGroups;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class AdminGroupsController extends BaseController {

    public function getIndex() {
        return View('backend.admin_groups.index', array('title' => "Groups"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();
        
        $sortColumn = array('','v_name', 'v_desc');
        $query = new AdminGroups;

        if (isset($data['v_name']) && $data['v_name'] != '') {
            $query = $query->where('v_name', 'LIKE', '%' . $data['v_name'] . '%');
        }
        if (isset($data['v_desc']) && $data['v_desc'] != '') {
            $query = $query->where('v_desc', 'LIKE', '%' . $data['v_desc'] . '%');
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
            $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            $data[$key][$index++] = $val['v_name'];
            $data[$key][$index++] = $val['v_desc'];
            
            $action = '';
            $action .= '<div class="d-flex">';
            $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'admin-groups/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';

            $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'admin-groups/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
            $record = new AdminGroups;
            $validator = Validator::make($inputs, [
                'v_name' => 'required', 
                'v_desc' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->v_name = trim($inputs['v_name']);
                $record->v_desc = trim($inputs['v_desc']);
                $record->created_at = Carbon::now();
                if ($record->save()) {                
                    Session::flash('success-message', 'Admin groups added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.admin_groups.add', array('title' => 'Add group'));
        }
        return Redirect(ADMIN_URL . 'admin-groups');
    }

    public function anyEdit(Request $request, $id) {
        
        $inputs  = $request->all();
        $record = AdminGroups::find($id);
        if ($record || !empty($record)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'v_name' => 'required', 
                    'v_desc' => 'required',            
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_name = trim($inputs['v_name']);
                    $record->v_desc = trim($inputs['v_desc']);
                    if ($record->save()) {
                        Session::flash('success-message', 'groups edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.admin_groups.edit', array('record' => $record, 'title' => 'Edit group'));
            }
        }
        return Redirect(ADMIN_URL . 'admin-groups');
    }

    public function getDelete($id) {
        $record = AdminGroups::find($id);
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
                $user_data = AdminGroups::whereIn('id', array_values($data['ids']))->get();
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