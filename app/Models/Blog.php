<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'blog';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

}
