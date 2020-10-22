<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationLuggageInfo extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'reservation_luggage_info';
    protected $dates = ['deleted_at'];

    public function SystemLuggageDef() {
        return $this->hasMany('App\Models\SystemLuggageDef','id','i_sys_luggage_id')->where('e_type','Luggage');
    }

    public function SystemAnimalDef() {
        return $this->hasMany('App\Models\SystemLuggageDef','id','i_sys_luggage_id')->where('e_type','Animal');
    }

}
