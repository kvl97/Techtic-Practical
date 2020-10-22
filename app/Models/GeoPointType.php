<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoPointType extends Model
{
    use SoftDeletes;
    protected $table = 'geo_point_types';

}