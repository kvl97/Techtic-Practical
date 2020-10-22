<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class TestimonialsController extends BaseController {

    public function getIndex() {
        return View('backend.testimonials.index', array('title' => "Testimonials"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_client_name','v_title','t_comment','e_status');
        $query = new Testimonials;

        if (isset($data['v_title']) && $data['v_title'] != '') {
            $query = $query->where('v_title', 'LIKE', '%' . $data['v_title'] . '%');
        }
        if (isset($data['t_comment']) && $data['t_comment'] != '') {
            $query = $query->where('t_comment', 'LIKE', '%' . $data['t_comment'] . '%');
        }
        if (isset($data['v_client_name']) && $data['v_client_name'] != '') {
            $query = $query->where('v_client_name', 'LIKE', '%' . $data['v_client_name'] . '%');
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status', $data['e_status']);
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
            if(isset($this->permission) && isset($this->permission[15]['i_delete']) && $this->permission[15]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid"><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_client_name'];
            $data[$key][$index++] = $val['v_title'];
            $data[$key][$index++] = $val['t_comment'] ? $val['t_comment'] :'';
            $data[$key][$index++] = $val['e_status'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[15]['i_add_edit']) && $this->permission[15]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'testimonials/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }
            if(isset($this->permission) && isset($this->permission[15]['i_delete']) && $this->permission[15]['i_delete'] == 1) {

                 $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'testimonials/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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

            $record = new Testimonials;
            $validator = Validator::make($inputs, [

                'v_title' => 'required',
                't_comment' => 'required',
                'v_client_name' => 'required',
                'e_status' => 'required'
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {

                $record->v_title = trim($inputs['v_title']);
                $record->t_comment = trim($inputs['t_comment']);
                $record->v_client_name = trim($inputs['v_client_name']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Testimonial added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.testimonials.add', array('title' => 'Add Testimonial'));
        }
        return Redirect(ADMIN_URL . 'testimonials');
    }
    
    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = Testimonials::find($id);
        if ($record || !empty($record)) {
            if ($inputs) {

                $validator = Validator::make($request->all(), [

                    'v_title' => 'required',
                    't_comment' => 'required',
                    'v_client_name' => 'required',
                    'e_status' => 'required'

                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_title = trim($inputs['v_title']);
                    $record->t_comment = trim($inputs['t_comment']);
                    $record->v_client_name = trim($inputs['v_client_name']);
                    $record->e_status = trim($inputs['e_status']);
                    if ($record->save()) {
                        Session::flash('success-message', 'Testimonial edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.testimonials.edit', array('record' => $record, 'title' => 'Edit Testimonial'));
            }
        }
        return Redirect(ADMIN_URL . 'testimonials');
    }

    public function getDelete($id) {
        $record = Testimonials::find($id);
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
                $user_data = Testimonials::whereIn('id', array_values($data['ids']))->get();
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

