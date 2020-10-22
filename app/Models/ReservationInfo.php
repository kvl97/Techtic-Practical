<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationInfo extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'reservation_info';
    protected $dates = ['deleted_at'];

    /* public function OneWaySystemIcaoDef() {
        return $this->hasOne('App\Models\SystemIcaoDef','id','i_oneway_airline_id');
    }

    public function ReturnSystemIcaoDef() {
        return $this->hasOne('App\Models\SystemIcaoDef','id','i_return_airline_id');
    } */
                  
}
