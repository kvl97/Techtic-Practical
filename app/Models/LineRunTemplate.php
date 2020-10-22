<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LineRunTemplate extends Authenticatable
{
    use Notifiable;
    protected $table = 'linerun_template';
}
