<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineRun extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'linerun';
    protected $dates = ['deleted_at'];

    public function LineRunRoute() {
        return $this->hasOne('App\Models\LineRunRoute','i_linerun_id','id');
    }

    public function GeoOriginServiceArea() {
        return $this->hasOne('App\Models\GeoServiceArea','id','i_origin_service_area_id');
    }

    public function GeoDestServiceArea() {
        return $this->hasOne('App\Models\GeoServiceArea','id','i_dest_service_area_id');
    }

    public function VehicleFleet() {
        return $this->hasOne('App\Models\FleetManager','id','i_vehicle_id');
    }

    public function VehicleFleetSpecification() {
        return $this->hasOne('App\Models\FleetVehicleSpecification','i_vehicle_id','i_vehicle_id');
    }

    public function Driver() {
        return $this->hasOne('App\Models\Admin','id','i_driver_id');
    }
    public function DriverExtension() {
        return $this->hasOne('App\Models\DriverExtension','i_driver_id','i_driver_id');
    }
    
}
