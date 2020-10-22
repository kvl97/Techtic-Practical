<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logs extends Model
{
    use SoftDeletes;
    protected $table = 'logs_activity';
    protected $dates = ['deleted_at'];

    public function CustomersLogs() {
        return $this->hasOne('App\Models\Customers','id','i_modified_by');
    }

    public function AdminLogs() {
        return $this->hasOne('App\Models\Admin','id','i_modified_by');
    }

    public function Reservations() {
        return $this->hasOne('App\Models\Reservations','id','i_rel_id');
    }
}