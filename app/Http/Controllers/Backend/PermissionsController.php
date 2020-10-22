<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminRoles;
use App\Models\Permissions;
use App\Models\Modules;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail, Session, Redirect, Validator, DB, Hash;
use Symfony\Component\Console\Input\Input;

class PermissionsController extends BaseController {

    public function getIndex() {
        $roles_list = AdminRoles::orderby('v_name','asc')->select('v_name','id')->where("id","<>",13)->get();
        return View('backend.permissions.index', array('title' => "Permissions", 'roles_list' => $roles_list));
    }

    public function getPermissionByRole($id) {
        $module_list = Modules::orderby('v_name','asc')->select('v_name','id')->get();
        $permission_list = Permissions::where('i_role_id', $id)->get()->keyBy('i_module_id')->toArray();
        return array('module_list' => $module_list, 'permission_list' => $permission_list);
    }

    public function anyAdd(Request $request) {

       $inputs = $request->all();
        if ($inputs) {
            $validator = Validator::make($inputs, [
                'i_role_id' => 'required',
                'i_module_id' => 'required',
            ]);

            if ($validator->fails()) {
                return json_encode($validator->errors());
            } else {
                if(isset($inputs['i_module_id'])) {
                    foreach($inputs['i_module_id'] as $val) {
                        $permission_obj = Permissions::where('i_role_id', $inputs['i_role_id'])->where('i_module_id',$val)->first();
                        if(empty($permission_obj)) {
                            $permission_obj = new Permissions();
                        }
                        $permission_obj->i_role_id = $inputs['i_role_id'];
                        $permission_obj->i_module_id = $val;
                        if(isset($inputs['i_list']) && in_array($val, $inputs['i_list'])) {
                            $permission_obj->i_list = 1;
                        } else {
                            $permission_obj->i_list = 0;
                        }

                        if(isset($inputs['i_add_edit']) && in_array($val, $inputs['i_add_edit'])) {
                            $permission_obj->i_add_edit = 1;
                        } else {
                            $permission_obj->i_add_edit = 0;
                        }

                        if(isset($inputs['i_edit']) && in_array($val, $inputs['i_edit'])) {
                            $permission_obj->i_edit = 1;
                        } else {
                            $permission_obj->i_edit = 0;
                        }

                        if(isset($inputs['i_delete']) && in_array($val, $inputs['i_delete'])) {
                            $permission_obj->i_delete = 1;
                        } else {
                            $permission_obj->i_delete = 0;
                        }
                        $permission_obj->save();
                    }
                }
                Session::flash('success-message', 'Permission has been updated successfully.');
                return '';
            }
        } else {
            return View('backend.admin_roles.add', array('title' => 'Add role'));
        }
        return Redirect(ADMIN_URL . 'admin-roles');
    }
}
