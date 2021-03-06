<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPages extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'cms_pages';
    protected $dates = ['deleted_at'];
    public $timestamps = true;

}
