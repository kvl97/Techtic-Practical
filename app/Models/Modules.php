<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Modules extends Model {

    use Notifiable;
    protected $table = 'modules';
    public $timestamps = true;
    public function Permission(){
        return $this->hasMany('App\Models\Permissions','i_module_id','id');
    }

}
