<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemNameDef extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'sys_name_def';
    protected $dates = ['deleted_at'];

}
