<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'comment';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

    public function User() {
        return $this->hasOne('App\Models\Customers','id','i_user_id');
    }

}
