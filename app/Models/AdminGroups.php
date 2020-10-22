<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminGroups extends Model {

    use Notifiable;
    use SoftDeletes;
    protected $table = 'admin_groups';    
    protected $dates = ['deleted_at'];
}
