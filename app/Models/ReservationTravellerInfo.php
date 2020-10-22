<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationTravellerInfo extends Model {

    use Notifiable;
    // use SoftDeletes;
    protected $table = 'reservation_traveller_info';
    public $timestamps = true;
    // protected $dates = ['deleted_at'];
    
}
