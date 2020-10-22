<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class HomePageContent extends Authenticatable
{
    use Notifiable;
    protected $table = 'home_page_contents';
    public $timestamps = true;
}
