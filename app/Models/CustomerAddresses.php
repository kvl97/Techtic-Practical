<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddresses extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'customer_addresses';
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id';

    public function CustomerDetails() {
        return $this->hasOne('App\Models\Customers', 'id', 'i_customer_id');
    }

}
