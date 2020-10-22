<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class FaqsController extends BaseController {

    public function getIndex() {
        return View('backend.faqs.index', array('title' => "FAQs"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('','v_question', 't_answer', 'i_order', 'e_status');
        $query = new Faqs;

        if (isset($data['v_question']) && $data['v_question'] != '') {
            $query = $query->where('v_question', 'LIKE', '%' . $data['v_question'] . '%');
        }
        if (isset($data['t_answer']) && $data['t_answer'] != '') {
            $query = $query->where('t_answer', 'LIKE', '%' . $data['t_answer'] . '%');
        }
        if (isset($data['i_order']) && $data['i_order'] != '') {
            $query = $query->where('i_order', '=', $data['i_order']);
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
            $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            $data[$key][$index++] = $val['v_question'];
            $data[$key][$index++] = $val['t_answer'];
            $data[$key][$index++] = $val['i_order'];
            $data[$key][$index++] = $val['e_status'];

            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[7]['i_add_edit']) && $this->permission[7]['i_add_edit'] == 1) {
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'faqs/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            }

            if(isset($this->permission) &&  isset($this->permission[7]['i_delete']) && $this->permission[7]['i_delete'] == 1) {
                $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'faqs/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
            $record = new Faqs;
            $validator = Validator::make($inputs, [
                'v_question' => 'required',
                't_answer' => 'required',
                'i_order' => [
                    'required',
                        Rule::unique('faqs','i_order'),
                    ],
                'e_status' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                $record->v_question = trim($inputs['v_question']);
                $record->t_answer = trim($inputs['t_answer']);
                $record->i_order = trim($inputs['i_order']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Faqs added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.faqs.add', array('title' => 'Add FAQ'));
        }
        return Redirect(ADMIN_URL . 'faqs');
    }

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = Faqs::find($id);

        if ($record || !empty($record)) {
            if ($inputs) {

                $validator = Validator::make($request->all(), [
                    'v_question' => 'required',
                    't_answer' => 'required',
                    'i_order' => 'required',
                    'e_status' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_question = trim($inputs['v_question']);
                    $record->t_answer = trim($inputs['t_answer']);
                    $record->i_order = trim($inputs['i_order']);
                    $record->e_status = trim($inputs['e_status']);
                    if ($record->save()) {
                        Session::flash('success-message', 'Faqs edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.faqs.edit', array('record' => $record, 'title' => 'Edit Faqs'));
            }
        }
        return Redirect(ADMIN_URL . 'faqs');
    }

    public function getDelete($id) {
        $record = Faqs::find($id);
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
            if ($data['action'] == 'Active') {
                if (Faqs::whereIn('id', $data['ids'])->update(array('e_status' => ACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Inactive') {
                if (Faqs::whereIn('id', $data['ids'])->update(array('e_status' => INACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Delete') {
                $user_data = Faqs::whereIn('id', array_values($data['ids']))->get();
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
?>
