<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;

class BlogController extends BaseController {

    public function getIndex() {
        return View('backend.blog.index', array('title' => "Blog"));
    }

    public function anyListAjax(Request $request) {
        $data = $request->all();

        $sortColumn = array('v_title','t_content','created_at');
        $query = new Blog;

        if (isset($data['v_title']) && $data['v_title'] != '') {
            $query = $query->where('v_title', 'LIKE', '%' . $data['v_title'] . '%');
        }
       
        if (isset($data['t_content']) && $data['t_content'] != '') {
            $query = $query->where('t_content', 'LIKE', '%' . $data['t_content'] . '%');
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

            $data[$key][$index++] = $val['v_title'];
            $data[$key][$index++] = $val['t_content'];
            $data[$key][$index++] =  date(DATE_FORMAT,strtotime($val['created_at']));

            $action = '';
            $action .= '<div class="d-flex">';
            
                $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'blog/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
            
            
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

            $record = new Blog;
            $validator = Validator::make($inputs, [

                'v_title' => 'required|unique:blog,v_title',
                't_content' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {

                $record->v_title = trim($inputs['v_title']);
                $content = str_replace(SITE_URL, '[SITE_URL]', $inputs['t_content']);
                $record->t_content = trim($content);
                $record->created_at = Carbon::now();
                if ($record->save()) {
                    Session::flash('success-message', 'Blog added successfully.');
                    return '';
                }
            }
        } else {
            return View('backend.blog.add', array('title' => 'Add Blog'));
        }
        return Redirect(ADMIN_URL . 'blog');
    }
    public function anyEdit(Request $request, $id) {

        $inputs  = $request->all();
        $record = Blog::find($id);
        $record->t_content = str_replace('[SITE_URL]', SITE_URL,$record->t_content);

        if ($record || !empty($record)) {
            if ($inputs) {
              
                $validator = Validator::make($request->all(), [
                    'v_title' => 'required|unique:blog,v_title,' . $id . '',
                    't_content' => 'required',

                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_title = trim($inputs['v_title']);
                    $content = str_replace(SITE_URL, '[SITE_URL]', $inputs['t_content']);
                    $record->t_content = trim($content);
                    if ($record->save()) {
                        Session::flash('success-message', 'Blog edited successfully.');
                        return '';
                    }
                }
            } else {
                return View('backend.blog.edit', array('record' => $record, 'title' => 'Edit Blog'));
            }
        }
        return Redirect(ADMIN_URL . 'blog');
    }

    public function getDelete($id) {
        $record = Blog::where('e_is_fixed_page',1)->find($id);
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
}

