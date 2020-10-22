<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customers extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'customers';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id';

    public function Reservations() {
        return $this->hasOne('App\Models\Reservations','i_customer_id','id');
    }

    protected static function boot() {
	    parent::boot();
        static::deleting(function($id) {
            if($id->Reservations()){
                foreach($id->Reservations()->get() as $val)
                {
                    $val->delete();
                }
            }
        });
    }
}
