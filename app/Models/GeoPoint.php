<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoPoint extends Model
{
    use SoftDeletes;
    
    
    protected $table = 'geo_point';
    public $timestamps = true;

    public function GeoServiceArea() {
        return $this->hasOne('App\Models\GeoServiceArea','id','i_service_area_id')->select('id', 'v_area_label');
    }
    
    public function GeoPointType() {
        return $this->hasOne('App\Models\GeoPointType','id','i_point_type_id')->select('id', 'v_label');
    }

    public function GeoCities() {
        return $this->hasOne('App\Models\GeoCities','id','i_city_id');
    }
}