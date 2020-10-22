<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin, App\Models\AdminGroups, App\Models\AdminRoles;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash;

class AdminUserController extends BaseController {

    public function getIndex() {
        $roles = AdminRoles::get()->toArray();
        $auth_user = auth()->guard('admin')->user();
        return View('backend.admin_users.index', array('title' => 'Users', 'auth_user' => $auth_user,'roles'=>$roles));
    }

    public function anyListAjax(Request $request) { //User Listing
        $auth_user = auth()->guard('admin')->user();
        $data = $request->all();

        $sortColumn = array('','v_firstname', 'v_lastname', 'v_email', 'v_phone', 'v_street','roles', 'e_status');
        $query = Admin::where('admin.id', '!=', $auth_user['id'])->where('admin.id',"<>",1)->join('admin_roles', function ($join) {
            $join->on('admin_roles.id', '=', 'admin.i_role_id');
        })->select(['admin.*','admin_roles.id as roles_id','admin_roles.v_name as roles'])->where('admin.i_role_id', '!=', 6);

        if (isset($data['v_firstname']) && $data['v_firstname'] != '') {
            $query = $query->where('v_firstname', 'LIKE', '%' . $data['v_firstname'] . '%');
        }
        if (isset($data['v_lastname']) && $data['v_lastname'] != '') {
            $query = $query->where('v_lastname', 'LIKE', '%' . $data['v_lastname'] . '%');
        }
        if (isset($data['v_email']) && $data['v_email'] != '') {
            $query = $query->where('v_email', 'LIKE', '%' . $data['v_email'] . '%');
        }
        if (isset($data['v_phone']) && $data['v_phone'] != '') {
            $query = $query->where('v_phone', 'LIKE', '%' . $data['v_phone'] . '%');
        }
        if (isset($data['v_address']) && $data['v_address'] != '') {
            $query = $query->where(DB::raw("CONCAT(v_street, ', ',v_city, ', ',v_state, ' - ',v_postal_code, ', ',v_country)"), 'LIKE', '%' . trim($data['v_address']) . '%');
        }
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status', "=", $data['e_status']);
        }
        if (isset($data['v_role']) && $data['v_role'] != '') {
            $query = $query->where('admin_roles.v_name', 'LIKE', '%' . $data['v_role'] . '%');
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

            // $chk_box = '<input type="checkbox" name="id[]" value="' . $val['id'] . '" class="delete_' . $val['id'] . '">';

            // $data[$key][$index++] = $chk_box;
            if(isset($this->permission) && isset($this->permission[1]['i_delete']) && $this->permission[1]['i_delete'] == 1) {
                $data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            }
            $data[$key][$index++] = $val['v_firstname'];
            $data[$key][$index++] = $val['v_lastname'];
            $data[$key][$index++] = $val['v_email'];
            $data[$key][$index++] = $val['v_phone'];
            $data[$key][$index++] = $val['v_street'] ? $val['v_street'].', '.$val['v_city'].', '.$val['v_state'].($val['v_postal_code'] ? ' - '.$val['v_postal_code'] : '').', '.$val['v_country'] : '';
            $data[$key][$index++] = $val['roles'];

            if($val['e_status'] == 'Active')
                $data[$key][$index++] = 'Active';
            else
                $data[$key][$index++] = 'Inactive';

            $action = '';
            $action .= '<div class="d-flex">';

            if ($val['id'] != $auth_user->id) {
                if(isset($this->permission) && isset($this->permission[1]['i_add_edit']) && $this->permission[1]['i_add_edit'] == 1) {
                    $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'admin-users/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
                }

                if(isset($this->permission) && isset($this->permission[1]['i_delete']) && $this->permission[1]['i_delete'] == 1) {
                    $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'admin-users/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
                }
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
        $current_user = auth()->guard('admin')->user();
        $id = $current_user->id;
        $inputs = $request->all();
        $record ='';
        // pr($inputs); exit;
        if ($inputs) {
            $record = new Admin;
            $validator = Validator::make($inputs, [
                'v_firstname' => 'required|max:100',
                'v_lastname' => 'required|max:100',
                'v_lastname' => 'required|max:100',
                'i_role_id' => 'required',
                // 'i_group_id' => 'required',
                'v_phone' => 'max:50',
                'v_street' => 'required',
                'v_city' => 'required',
                'v_state' => 'required',
                'v_country' => 'required',
                'v_postal_code' => 'required',
                'e_status' => 'required',
                "v_email" => 'required|unique:admin,v_email,NULL,id,deleted_at,NULL',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());

            } else {

                $record->v_firstname = trim($inputs['v_firstname']);
                $record->v_lastname = trim($inputs['v_lastname']);
                $record->v_email = trim($inputs['v_email']);
                $record->v_phone = trim($inputs['v_phone']);
                $record->v_street = trim($inputs['v_street']);
                $record->v_city = trim($inputs['v_city']);
                $record->v_state = trim($inputs['v_state']);
                $record->v_country = trim($inputs['v_country']);
                $record->v_postal_code = trim($inputs['v_postal_code']);
                $record->i_role_id = trim($inputs['i_role_id']);
                // $record->i_group_id = trim($inputs['i_group_id']);
                if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                    $profileImgPath = ADMIN_USER_PROFILE_IMG_PATH;
                    $profileImgThumbPath = ADMIN_FILES_PATH;

                    $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath);
                    $record->v_profile_image = $imageName;
                }
                $record->password = Hash::make($inputs['password']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();


                if ($record->save()) {
                    Session::flash('success-message', 'User added successfully.');
                    return '';
                }
            }

        } else {
            $adminGroups = AdminGroups::where('id', '!=', 3)->pluck('v_name', 'id');
            
            $adminRoles = AdminRoles::whereNotIn('id',[6,13])->pluck('v_name', 'id');
            return View('backend.admin_users.add', array('title' => 'Add User', 'adminGroups' => $adminGroups, 'adminRoles' => $adminRoles, 'records'=>$record));
        }

        return Redirect(ADMIN_URL . 'admin_users');

    }

    public function anyEdit(Request $request, $id) {
        $current_user = auth()->guard('admin')->user();
        $inputs  = $request->all();
        $record = Admin::find($id);
        if($id == 1) {
            return Redirect(ADMIN_URL . 'admin-users');
        }
        if ($record || !empty($record)) {
            if ($inputs) {

                $validator = Validator::make($request->all(), [
                    "v_email" => 'required|unique:admin,v_email,' . $id . ',id,deleted_at,NULL',
                    'v_firstname' => 'required|max:100',
                    'v_lastname' => 'required|max:100',
                    'v_lastname' => 'required|max:100',
                    'i_role_id' => 'required',
                    // 'i_group_id' => 'required',
                    'v_phone' => 'max:50',
                    'v_street' => 'required',
                    'v_city' => 'required',
                    'v_state' => 'required',
                    'v_country' => 'required',
                    'v_postal_code' => 'required',
                    'e_status' => 'required',
                ]);

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_firstname = trim($inputs['v_firstname']);
                    $record->v_lastname = trim($inputs['v_lastname']);
                    $record->v_email = trim($inputs['v_email']);
                    $record->v_phone = trim($inputs['v_phone']);
                    $record->v_street = trim($inputs['v_street']);
                    $record->v_city = trim($inputs['v_city']);
                    $record->v_state = trim($inputs['v_state']);
                    $record->v_country = trim($inputs['v_country']);
                    $record->v_postal_code = trim($inputs['v_postal_code']);
                    if($id == 1) {
                        $record->i_role_id = $record->i_role_id;
                    } else {
                        $record->i_role_id = trim($inputs['i_role_id']);
                    }
                    // $record->i_group_id = trim($inputs['i_group_id']);

                    if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                        $profileImgPath = ADMIN_USER_PROFILE_IMG_PATH;
                        $profileImgThumbPath = ADMIN_USER_PROFILE_THUMB_IMG_PATH;

                        $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath);

                        if($record->v_profile_image != '') {
                            if(file_exists($profileImgPath . $record->v_profile_image)) {
                                @unlink($profileImgPath.($record->v_profile_image));
                            }
                            if(file_exists($profileImgThumbPath . $record->v_profile_image)) {
                                @unlink($profileImgThumbPath.($record->v_profile_image));
                            }
                        }
                        $record->v_profile_image = $imageName;
                    } elseif($inputs['imgbase64'] == '' && $record->v_profile_image != '') {
                        @unlink(ADMIN_USER_PROFILE_THUMB_IMG_PATH. $record->v_profile_image);
                        @unlink(ADMIN_USER_PROFILE_IMG_PATH.($record->v_profile_image));
                        $record->v_profile_image = '';
                    } else {
                        $record->v_profile_image = $record->v_profile_image;
                    }

                    if ($inputs['password'] != "" && $inputs['cpassword'] != "") {
                        $record->password = Hash::make($inputs['password']);
                    }

                    $record->e_status = trim($inputs['e_status']);
                    if ($record->save()) {
                        Session::flash('success-message', 'User edited successfully.');
                        return '';
                    }
                }
            } else {
                if ($id != $current_user->id) {
                    $adminGroups = AdminGroups::where('id', '!=', 3)->pluck('v_name', 'id');
                    $adminRoles = AdminRoles::whereNotIn('id', [6,13])->pluck('v_name', 'id');
                        return View('backend.admin_users.edit', array('record' => $record, 'title' => 'Edit User', 'adminGroups' => $adminGroups, 'adminRoles' => $adminRoles));
                }
            }
        }

        return Redirect(ADMIN_URL . 'admin-users');
    }

    public function postChangeStatus() {
        $data = $request->all();
        if (!empty($data)) {
            if ($data['data'] == 'true') {
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }
            $user = Admin::find($data['id']);
            $user->e_status = $status;
            if ($user->save()) {
                return 'TRUE';
            } else {
                return 'FALSE';
            }
        }
        return "TRUE";
    }

    public function getDelete($id) {
        if($id == 1) {
            return 'FALSE';
        }
        $user = Admin::find($id);
        
        if (!empty($user)) {
            $exist_user_count = Admin::onlyTrashed()->Where('v_email', 'LIKE', '%dl_' . $user->v_email)->count();
            if ($exist_user_count > 0) {
                $user->v_email = ($exist_user_count + 1) . '_dl_' . $user->v_email;
            } else {
                $user->v_email = '1_dl_' . $user->v_email;
            }
            // pr(WWW_ROOT.'/'.ADMIN_USER_PROFILE_THUMB_IMG_PATH. $user->v_profile_image); exit;

            $user->save();
            if ($user->delete()) {
                @unlink(WWW_ROOT.'/'.ADMIN_USER_PROFILE_THUMB_IMG_PATH. $user->v_profile_image);
                @unlink(WWW_ROOT.'/'.ADMIN_USER_PROFILE_IMG_PATH.($user->v_profile_image));
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
                if (Admin::whereIn('id', $data['ids'])->update(array('e_status' => ACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Inactive') {
                if (Admin::whereIn('id', $data['ids'])->update(array('e_status' => INACTIVE_STATUS))) {
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            } else if ($data['action'] == 'Delete') {
                $user_data = Admin::whereIn('id', array_values($data['ids']))->where('id',"<>",1)->get();
                if ($user_data) {
                    foreach ($user_data as $data) {

                        $exist_user_count = Admin::onlyTrashed()->Where('v_email', 'LIKE', '%dl_' . $data->v_email)->count();

                        if ($exist_user_count > 0) {
                            $data->v_email = ($exist_user_count + 1) . '_dl_' . $data->v_email;
                        } else {
                            $data->v_email = '1_dl_' . $data->v_email;
                        }

                        $data->deleted_at = Carbon::now();
                        $data->save();
                        $data->delete();
                        @unlink(WWW_ROOT.'/'.ADMIN_USER_PROFILE_THUMB_IMG_PATH. $data->v_profile_image);
                        @unlink(WWW_ROOT.'/'.ADMIN_USER_PROFILE_IMG_PATH.($data->v_profile_image));
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }

}
