<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SystemPaymentDef extends Authenticatable
{
    use Notifiable;
    protected $table = 'sys_paymethod_def';
    public $timestamps = true;

}
