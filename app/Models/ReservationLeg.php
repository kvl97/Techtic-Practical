<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationLeg extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'reservation_leg';
    public $timestamps = true;

    public function LineRune(){
        return $this->hasOne('App\Models\LineRun','id','i_run_id');
    }

    public function ReservAtionInfo(){
        return $this->hasOne('App\Models\Reservations','id','i_reservation_id')->where('e_reservation_status','Booked');
    }

   


}
