<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminRoles extends Model {

    use Notifiable;
    use SoftDeletes;
    protected $table = 'admin_roles';    
    protected $dates = ['deleted_at'];

    public function Admin() {
        return $this->hasMany('App\Models\Admin','i_role_id','id');
    }

}