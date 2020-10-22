<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Models\Permissions;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'admin';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'v_email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasPermission($module_id, $action){
        $role_id = Auth::guard('admin')->user()->i_role_id;
        $permissions = Permissions::where('i_module_id', $module_id)->where('i_role_id',$role_id)->first();
        if($action == 'i_list') {
            if(isset($permissions->i_list) && $permissions->i_list == 1) {
                return true;
            } else {
                return false;
            }
        } else if($action == 'i_add_edit') {
            if(isset($permissions->i_add_edit) && $permissions->i_add_edit == 1) {
                return true;
            } else {
                return false;
            }
        } else if($action == 'i_delete') {
            if(isset($permissions->i_delete) && $permissions->i_delete == 1) {
                return true;
            } else {
                return false;
            }
        }
	}

    public function DriverIds() {
        return $this->hasMany('App\Models\LineRun','i_driver_id','id');
    }

    public function DriverExtension() {
        return $this->hasMany('App\Models\DriverExtension','i_driver_id','id');
    }

    public function AdminRole() {
        return $this->hasMany('App\Models\AdminRoles','id','i_role_id');
    }

    public function Permissions() {
        return $this->hasone('App\Models\Permissions','i_role_id','i_role_id');
    }
}
