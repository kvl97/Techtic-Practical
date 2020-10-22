<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetManager extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'fleet_vehicle';
    protected $dates = ['deleted_at'];

    public function get_vehicle_specification() {
        return $this->hasOne('App\Models\FleetVehicleSpecification', 'i_vehicle_id', 'id');
    }

}
