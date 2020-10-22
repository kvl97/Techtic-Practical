<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemNameDef;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class SystemNameDefController extends BaseController {

    public function getIndex() {
        return View('backend.system_name_def.index', array('title' => "Prefix/Suffix Name Definition"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','e_type','v_label', 'v_label_desc');
        $query = new SystemNameDef;

        if (isset($data['v_label']) && $data['v_label'] != '') {
            $query = $query->where('v_label', 'LIKE', '%' . $data['v_label'] . '%');
        }
        if (isset($data['v_label_desc']) && $data['v_label_desc'] != '') {
            $query = $query->where('v_label_desc', 'LIKE', '%' . $data['v_label_desc'] . '%');
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
            $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            $data[$key][$index++] = $val['e_type'];
            $data[$key][$index++] = $val['v_label'];
            $data[$key][$index++] = $val['v_label_desc'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[12]['i_add_edit']) &&$this->permission[12]['i_add_edit'] == 1) {
                $action = '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'system-name-def/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) && isset($this->permission[12]['i_delete']) && $this->permission[12]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'system-name-def/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
            $record = new SystemNameDef;
            $validator = Validator::make($inputs, [
                'v_label' => 'required',
                'v_label_desc' => 'required',
                'e_type' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->v_label = trim($inputs['v_label']);
                $record->v_label_desc = trim($inputs['v_label_desc']);
                $record->e_type = trim($inputs['e_type']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Label added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.system_name_def.add', array('title' => 'Add Name Definition'));
        }
        return Redirect(ADMIN_URL . 'system-name-def');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = SystemNameDef::find($id);
        if ($record || !empty($record)) {
            if ($inputs) {
                $validator = Validator::make($request->all(), [
                    'v_label' => 'required',
                    'v_label_desc' => 'required',
                    'e_type' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_label = trim($inputs['v_label']);
                    $record->v_label_desc = trim($inputs['v_label_desc']);
                    $record->e_type = trim($inputs['e_type']);
                    if ($record->save()) {
                        Session::flash('success-message', 'Label edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.system_name_def.edit', array('record' => $record, 'title' => 'Edit Name Definition'));
            }
        }
        return Redirect(ADMIN_URL . 'system-name-def');
    }

    public function getDelete($id) {
        $record = SystemNameDef::find($id);
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
            if ($data['action'] == 'Prefix') {
                if (SystemNameDef::whereIn('id', array_values($data['ids']))->update(array('e_type' => 'Prefix'))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Suffix') {
                if (SystemNameDef::whereIn('id', array_values($data['ids']))->update(array('e_type' => 'Suffix'))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Delete') {
                $user_data = SystemNameDef::whereIn('id', array_values($data['ids']))->get();
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
