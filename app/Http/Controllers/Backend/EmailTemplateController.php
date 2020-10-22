<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Illuminate\Validation\Rule;

class EmailTemplateController extends BaseController {

    public function getIndex() {
        return View('backend.email_template.index', array('title' => "Email Templates"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('v_template_title', 'v_template_subject');
        $query = new EmailTemplate;

        if (isset($data['v_template_title']) && $data['v_template_title'] != '') {
            $query = $query->where('v_template_title', 'LIKE', '%' . $data['v_template_title'] . '%');
        }
        if (isset($data['v_template_subject']) && $data['v_template_subject'] != '') {
            $query = $query->where('v_template_subject', 'LIKE', '%' . $data['v_template_subject'] . '%');
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
        if(isset($data['order']['0']['column']) && $data['order']['0']['column'] == 2) {
            $order_field = '';
        } else {
            $order_field = $sortColumn[$data['order']['0']['column']];
        }
        if ($sort_order != '' && $order_field != '') {
            $query = $query->orderBy($order_field, $sort_order);
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        
        $arrUsers = $users->toArray();
        $data = array();
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            $data[$key][$index++] = $val['v_template_title'];
            $data[$key][$index++] = $val['v_template_subject'];
            
            $action = '';
            $action .= '<div class="d-flex">';
            if(isset($this->permission) && isset($this->permission[24]['i_add_edit']) && $this->permission[24]['i_add_edit'] == 1) {

                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'email-template/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
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

    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = EmailTemplate::find($id);
        $record->t_email_content = str_replace('[SITE_URL]', SITE_URL,$record->t_email_content);
        if ($record || !empty($record)) {
            if ($inputs) {

                $validator = Validator::make($request->all(), [
    
                    'v_template_title' => 'required',
                    'v_template_subject' => 'required',
                    't_email_content' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $content = str_replace(SITE_URL, '[SITE_URL]', $inputs['t_email_content']);
                    $record->v_template_title = trim($inputs['v_template_title']);
                    $record->v_template_subject = trim($inputs['v_template_subject']);
                    $record->t_email_content = trim($content);
                    $record->updated_at = Carbon::now();
                    
                    if ($record->save()) {
                        Session::flash('success-message', 'Email Template edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.email_template.edit', array('record' => $record, 'title' => 'Edit Email Template'));
            }
        }
        return Redirect(ADMIN_URL .'email_template');
    }

   
}
