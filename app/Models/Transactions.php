<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    use SoftDeletes;
    protected $table = 'transactions';

    public function Reservation() {
        return $this->hasOne('App\Models\Reservations','id','i_reservation_id');
    }

    public function Customer() {
        return $this->hasOne('App\Models\Customers','id','i_customer_id');
    }
}