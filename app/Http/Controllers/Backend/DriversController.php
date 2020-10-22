<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin, App\Models\AdminGroups, App\Models\AdminRoles, App\Models\DriverExtension;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Mail, Session, Redirect, Validator, DB, Hash;

class DriversController extends BaseController {

    public function getIndex() {
        $auth_user = auth()->guard('admin')->user();
        return View('backend.drivers.index', array('title' => 'Drivers', 'auth_user' => $auth_user));
    }

    public function anyListAjax(Request $request) { //User Listing
        $auth_user = auth()->guard('admin')->user();
        $data = $request->all();

        $sortColumn = array('extension','v_firstname','v_dispatch_name', 'v_phone','v_email', 'v_street', 'e_status');

        $query = Admin::join('driver_extension', function ($join) {
            $join->on('driver_extension.i_driver_id', '=', 'admin.id');
        })->select(['admin.*','driver_extension.i_driver_id as driver_id','driver_extension.v_extension as extension'])->where(['admin.i_role_id' => 6]);

        /* if (isset($data['v_firstname']) && $data['v_firstname'] != '') {
            $query = $query->where('v_firstname', 'LIKE', '%' . $data['v_firstname'] . '%');
        }
        if (isset($data['v_lastname']) && $data['v_lastname'] != '') {
            $query = $query->where('v_lastname', 'LIKE', '%' . $data['v_lastname'] . '%');
        } */
        if (isset($data['v_firstname']) && $data['v_firstname'] != '') {
            $query = $query->where(DB::raw("CONCAT(v_firstname, ' ',v_lastname)"), 'LIKE', '%' . trim($data['v_firstname']) . '%');
        }
        if (isset($data['v_dispatch_name']) && $data['v_dispatch_name'] != '') {
            $query = $query->where('v_dispatch_name', 'LIKE', '%' . $data['v_dispatch_name'] . '%');
        }
        if (isset($data['v_phone']) && $data['v_phone'] != '') {
            $query = $query->where('v_phone', 'LIKE', '%' . $data['v_phone'] . '%');
        }
        if (isset($data['v_email']) && $data['v_email'] != '') {
            $query = $query->where('v_email', 'LIKE', '%' . $data['v_email'] . '%');
        }
       if (isset($data['v_address']) && $data['v_address'] != '') {
            $query = $query->where(DB::raw("CONCAT(v_street, ', ',v_city, ', ',v_state, ' - ',v_postal_code, ', ',v_country)"), 'LIKE', '%' . trim($data['v_address']) . '%');
        }
        if (isset($data['v_extension']) && $data['v_extension'] != '') {
            $query = $query->where('driver_extension.v_extension', 'LIKE', '%' . $data['v_extension'] . '%');
        }
       /*  if (isset($data['v_extension']) && $data['v_extension'] != '') {
            $query = $query->whereHas('DriverExtension', function($q) use($data){
               $q = $q->where('driver_extension.v_extension', 'LIKE', '%' . $data['v_extension'] . '%');
            });
        } */
        if (isset($data['e_status']) && $data['e_status'] != '') {
            $query = $query->where('e_status', "=", $data['e_status']);
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
            if($order_field == 'v_extension') {
               $query = $query->join('driver_extension','driver_extension.i_driver_id','=','admin.id')->orderBy('driver_extension.v_extension',$sort_order);
            } else {
                $query = $query->orderBy($order_field, $sort_order);
            }
        } else {
            $query = $query->orderBy('updated_at', 'desc');
        }
        $users = $query->paginate($rec_per_page);
        ;
        $arrUsers = $users->toArray();
        $data = array();
        // pr($arrUsers['data']); exit;
        foreach ($arrUsers['data'] as $key => $val) {
            $index = 0;
            
            //$data[$key][$index++] = '<label class="kt-checkbox kt-checkbox--single kt-checkbox--solid "><input type="checkbox" name="id[]" value="' . $val['id'] . '" class="kt-checkable"><span></span></label>';
            $data[$key][$index++] = ($val['extension']) ? $val['extension'] : '';
           /*  if(isset($val['v_profile_image'])) {

                $imgname = '<a data-fancybox="gallery" href="'.SITE_URL."".DRIVER_PROFILE_IMG_PATH."".$val['v_profile_image'].'"><img width="100px" src="'.SITE_URL."".DRIVER_PROFILE_THUMB_IMG_PATH."".$val['v_profile_image'].'"></a>';
                $data[$key][$index++] = $imgname;

            }else {
                $data[$key][$index++] = "";
            } */
           
            $data[$key][$index++] = $val['v_firstname'].' '.$val['v_lastname'];
            $data[$key][$index++] = $val['v_dispatch_name'];
            $data[$key][$index++] = $val['v_phone'];
            $data[$key][$index++] = $val['v_email'];
            $data[$key][$index++] = $val['v_street'] ? $val['v_street'].', '.$val['v_city'].', '.$val['v_state'].($val['v_postal_code'] ? ' - '.$val['v_postal_code'] : '').', '.$val['v_country'] : '';
           

            if($val['e_status'] == 'Active'){
                $data[$key][$index++] = 'Active';
            }
            else if($val['e_status'] == 'Inactive'){

                $data[$key][$index++] = 'Inactive';
            
            }else{

                $data[$key][$index++] = 'Terminated';
            }

            $action = '';
            $action .= '<div class="d-flex">';
            if ($val['id'] != $auth_user->id) {
                if(isset($this->permission) && isset($this->permission[2]['i_add_edit']) && $this->permission[2]['i_add_edit'] == 1) {
                    $action .= '<a class="btn btn-sm btn-clean btn-icon btn-icon-lg" rel="' . $val['id'] . '" href="' . ADMIN_URL . 'drivers/edit/' . $val['id'] . '" title="edit"><i class="la la-edit"></i> </a>';
                }

                if(isset($this->permission) && isset($this->permission[2]['i_delete']) && $this->permission[2]['i_delete'] == 1) {
                   $action .= '<a title="Delete" id="delete_record" class="btn btn-sm btn-clean btn-icon btn-icon-lg delete_record" href="javascript:;" rel="' . $val['id'] . '" delete-url="' . ADMIN_URL . 'drivers/delete/' . $val['id'] . '"><i class="la la-trash"></i></a>';
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
            
            Validator::extend('customUnique', function($attribute, $value, $parameters, $validator) {
                $status = true;                    
                
                //check extension already exists in another data count
                $checkExtension = DriverExtension::join('admin', 'admin.id', 'driver_extension.i_driver_id')->where(['v_extension' => $value])->where('admin.e_status','!=','Terminated')->count();
                if($checkExtension > 0) {
                    $status = false;
                }
                
                return $status;
            }, 'The extension has already been taken.');
            
            $rules = [
                'v_firstname' => 'required|max:100',
                'v_lastname' => 'required|max:100',
                'v_dispatch_name' => 'required',
                'v_phone' => 'max:50',
                'v_street' => 'required',
                'v_city' => 'required',
                'v_state' => 'required',
                'v_country' => 'required',
                'v_postal_code' => 'required',
                'e_status' => 'required',
                "v_email" => 'required|unique:admin,v_email,NULL,id,deleted_at,NULL',
            ];
            $status =  trim($inputs['e_status']);
            if($status == 'Terminated') {
                $rules['v_extension'] = 'required';
            } else {
                $rules['v_extension'] = 'required|custom-unique';
            }
            
            $validator = Validator::make($inputs, $rules);

            if ($validator->fails()) {
                return json_encode($validator->errors());

            } else {

                $record->v_firstname = trim($inputs['v_firstname']);
                $record->v_lastname = trim($inputs['v_lastname']);
                $record->v_dispatch_name = trim($inputs['v_dispatch_name']);
                $record->i_role_id = 6;
                $record->v_email = trim($inputs['v_email']);
                
                $record->v_phone = trim($inputs['v_phone']);
                $record->v_street = trim($inputs['v_street']);
                $record->v_city = trim($inputs['v_city']);
                $record->v_state = trim($inputs['v_state']);
                $record->v_country = trim($inputs['v_country']);
                $record->v_postal_code = trim($inputs['v_postal_code']);
                
                if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                    $profileImgPath = DRIVER_PROFILE_IMG_PATH;
                    $profileImgThumbPath = DRIVER_PROFILE_THUMB_IMG_PATH;

                    $imageName = $this->cropImages($inputs['imgbase64'], $inputs['x'], $inputs['y'], $inputs['h'], $inputs['w'], $profileImgPath, $profileImgThumbPath);
                    $record->v_profile_image = $imageName;
                }
                $record->password = Hash::make($inputs['password']);
                $record->e_status = trim($inputs['e_status']);
                $record->created_at = Carbon::now();


                if ($record->save()) {

                    $addExtension = new DriverExtension;
                    $addExtension->i_driver_id = $record->id;
                    $addExtension->v_extension = trim($inputs['v_extension']);
                    $addExtension->save();

                    Session::flash('success-message', 'Driver added successfully.');
                    return '';
                }
            }

        } else {

            return View('backend.drivers.add', array('title' => 'Add Driver','records'=>$record));
        }

        return Redirect(ADMIN_URL . 'drivers');

    }

    public function anyEdit(Request $request, $id) {
        $current_user = auth()->guard('admin')->user();
        $inputs  = $request->all();
        $record = Admin::find($id);
        $driver_extension = DriverExtension::where('i_driver_id', $id)->get()->toArray();
        // pr($record); exit;
        if ($record || !empty($record)) {
            if ($inputs) {
                Validator::extend('customUnique', function($attribute, $value, $parameters, $validator) use($id){
                    $status = true;                    
                    
                    //check extension already exists in another data count
                    $checkExtension = DriverExtension::join('admin', 'admin.id', 'driver_extension.i_driver_id')->where(['v_extension' => $value])->where('admin.e_status','!=','Terminated')->where('admin.id','!=', $id)->count();
                    if($checkExtension > 0) {
                        $status = false;
                    }
                    
                    return $status;
                }, 'The extension has already been taken.');
                
                $rules = [
                    'v_firstname' => 'required|max:100',
                    'v_lastname' => 'required|max:100',
                    'v_dispatch_name' => 'required',
                    'v_phone' => 'max:50',
                    'v_street' => 'required',
                    'v_city' => 'required',
                    'v_state' => 'required',
                    'v_country' => 'required',
                    'v_postal_code' => 'required',
                    'e_status' => 'required',
                    "v_email" => 'required|unique:admin,v_email,' . $id . ',id,deleted_at,NULL',
                ];
                $status =  trim($inputs['e_status']);
                if($status == 'Terminated') {
                    $rules['v_extension'] = 'required';
                } else {
                    $rules['v_extension'] = 'required|custom-unique';
                }
                
                $validator = Validator::make($inputs, $rules);
                

                if ($validator->fails()) {
                    return json_encode($validator->errors());
                } else {
                    $record->v_firstname = trim($inputs['v_firstname']);
                    $record->v_lastname = trim($inputs['v_lastname']);
                    $record->v_dispatch_name = trim($inputs['v_dispatch_name']);
                    $record->v_email = trim($inputs['v_email']);
                    $record->v_phone = trim($inputs['v_phone']);
                    $record->v_street = trim($inputs['v_street']);
                    $record->v_city = trim($inputs['v_city']);
                    $record->v_state = trim($inputs['v_state']);
                    $record->v_country = trim($inputs['v_country']);
                    $record->v_postal_code = trim($inputs['v_postal_code']);
                    
                    if (isset($inputs['imgbase64']) && $inputs['imgbase64'] != '' && $inputs['is_edit_img_flag'] == '1') {
                        $profileImgPath = DRIVER_PROFILE_IMG_PATH;
                        $profileImgThumbPath = DRIVER_PROFILE_THUMB_IMG_PATH;

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
                        @unlink(DRIVER_PROFILE_THUMB_IMG_PATH. $record->v_profile_image);
                        @unlink(DRIVER_PROFILE_IMG_PATH.($record->v_profile_image));
                        $record->v_profile_image = '';
                    } else {
                        $record->v_profile_image = $record->v_profile_image;
                    }

                    if ($inputs['password'] != "" && $inputs['cpassword'] != "") {
                        $record->password = Hash::make($inputs['password']);
                    }

                    $record->e_status = trim($inputs['e_status']);
                    if ($record->save()) {

                        $addExtension = DriverExtension::where('i_driver_id', $id)->first();
                        $addExtension->i_driver_id = $record->id;
                        $addExtension->v_extension = trim($inputs['v_extension']);
                        $addExtension->save();

                        Session::flash('success-message', 'Driver information edited successfully.');
                        return '';
                    }
                }
            } else {
                if ($id != $current_user->id) {

                        return View('backend.drivers.edit', array('record' => $record, 'title' => 'Edit Drivers', 'driver_extension' => $driver_extension));
                }
            }
        }

        return Redirect(ADMIN_URL . 'drivers');
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
        // pr("here"); exit;
        $user = Admin::find($id);
        if (!empty($user)) {
            $exist_user_count = Admin::onlyTrashed()->Where('v_email', 'LIKE', '%dl_' . $user->v_email)->count();
            if ($exist_user_count > 0) {
                $user->v_email = ($exist_user_count + 1) . '_dl_' . $user->v_email;
            } else {
                $user->v_email = '1_dl_' . $user->v_email;
            }
            // pr(WWW_ROOT.'/'.DRIVER_PROFILE_THUMB_IMG_PATH. $user->v_profile_image); exit;

            $user->save();
            if ($user->delete()) {
                @unlink(WWW_ROOT.'/'.DRIVER_PROFILE_THUMB_IMG_PATH. $user->v_profile_image);
                @unlink(WWW_ROOT.'/'.DRIVER_PROFILE_IMG_PATH.($user->v_profile_image));
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
                $user_data = Admin::whereIn('id', array_values($data['ids']))->get();
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
                        @unlink(WWW_ROOT.'/'.DRIVER_PROFILE_THUMB_IMG_PATH. $data->v_profile_image);
                        @unlink(WWW_ROOT.'/'.DRIVER_PROFILE_IMG_PATH.($data->v_profile_image));
                    }
                    return 'TRUE';
                } else {
                    return 'FALSE';
                }
            }
        }
    }

}
