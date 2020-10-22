<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservations extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'reservations';
    protected $dates = ['deleted_at'];

    public function GeoOriginServiceArea() {
        return $this->hasOne('App\Models\GeoPoint','id','i_origin_point_id')->select('id', 'v_label', 'v_street1', 'v_postal_code', 'i_city_id');
    }

    public function GeoDestServiceArea() {
        return $this->hasOne('App\Models\GeoPoint','id','i_destination_point_id')->select('id', 'v_label', 'v_street1', 'v_postal_code', 'i_city_id');
    }

    public function Admin() {
        return $this->hasOne('App\Models\Admin','id','i_admin_id')->select('id', 'v_firstname', 'v_lastname');
    }
    
    public function ApprovedByAdmin() {
        return $this->hasOne('App\Models\Admin','id','i_private_approved_by')->select('id', 'v_firstname', 'v_lastname');
    }

    public function Customers() {
        return $this->hasOne('App\Models\Customers','id','i_customer_id')->select('id', 'v_firstname', 'v_lastname','v_email');
    }

    public function SystemResCategory() {
        return $this->hasOne('App\Models\SystemResCategory','id','i_reservation_category_id')->select('id', 'v_label');
    }

    public function ReservationTravellerInfo() {
        return $this->hasMany('App\Models\ReservationTravellerInfo','i_reservation_id','id');
    }

    public function ReservationInfo() {
        return $this->hasOne('App\Models\ReservationInfo','i_reservation_id','id');
    }

    public function ReservAtionLeg(){
        return $this->hasOne('App\Models\ReservationLeg','i_reservation_id','id');
    }

    public function Transactions() {
        return $this->hasMany('App\Models\Transactions','i_reservation_id','id');
    }

    public function PickupCity() {
        return $this->hasOne('App\Models\GeoCities','id','i_pickup_city_id');
    }
    public function DropOffCity() {
        return $this->hasOne('App\Models\GeoCities','id','i_dropoff_city_id');
    }
    public function ReservationLuggageInfo() {
        return $this->hasMany('App\Models\ReservationLuggageInfo','i_reservation_id','id');
    }

    public function ReservationLogs() {
        return $this->hasMany('App\Models\Logs','i_rel_id','id');
    }
    protected static function boot() {
	    parent::boot();
        static::deleting(function($id) {
            if($id->ReservationTravellerInfo()){
                foreach($id->ReservationTravellerInfo()->get() as $val)
                {
                    $val->delete();
                }
            }
        });
    }
    public function AdminBookedBy(){
        return $this->hasOne('App\Models\Admin','id','added_by_id');
    }


}
