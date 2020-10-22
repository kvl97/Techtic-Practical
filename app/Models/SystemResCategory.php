<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemResCategory extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'geo_point_types';
    protected $dates = ['deleted_at'];

}
