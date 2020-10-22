<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoCities extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'geo_cities';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id';

    public function GeoServiceArea() {
        return $this->hasOne('App\Models\GeoServiceArea','id','i_service_area_id')->select('id', 'v_area_label');
    }

    public function GeoPoint() {
        return $this->hasOne('App\Models\GeoPoint','i_city_id','id');
    }

    public function GeoPoints() {
        return $this->hasMany('App\Models\GeoPoint','i_city_id','id')->select('id','v_label','v_street1','i_city_id');
    }
}