<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model {

    use Notifiable;
    use SoftDeletes;
    protected $table = 'permission';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    public function Roles(){
        return $this->hasOne('App\Models\AdminRoles','id','i_role_id');
    }
    public function Module(){
        return $this->hasOne('App\Models\Modules','id','i_module_id');
    }

}
