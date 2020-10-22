<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetVehicleSpecification extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'fleet_vehicle_spec';
    protected $dates = ['deleted_at'];

}
