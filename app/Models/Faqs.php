<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'faqs';
    protected $dates = ['deleted_at'];

}
