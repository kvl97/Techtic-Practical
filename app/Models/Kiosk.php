<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kiosk extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'kiosk';
    protected $dates = ['deleted_at'];

    public function DriverName() {
        return $this->hasOne('App\Models\Admin','id','i_driver_id');
    }

    public function VehicleCode() {
        return $this->hasOne('App\Models\FleetManager','id','v_van_id');
    }

    public function DriverExtension() {
        return $this->hasOne('App\Models\DriverExtension','i_driver_id','i_driver_id');
    }
}