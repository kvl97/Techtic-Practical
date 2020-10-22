<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonials extends Authenticatable
{

    use Notifiable;
    use SoftDeletes;
    protected $table = 'testimonials';
    protected $dates = ['deleted_at'];
}
