<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'contact_us';
    protected $dates = ['deleted_at'];

}
