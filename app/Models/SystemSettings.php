<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SystemSettings extends Authenticatable
{
    use Notifiable;
    protected $table = 'sys_settings';
    public $timestamps = false;

}
