<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverExtension extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'driver_extension';
    protected $dates = ['deleted_at'];

    public function DriverExtension() {
        return $this->hasOne('App\Models\Admin','i_driver_id','i_driver_id');
    }

}