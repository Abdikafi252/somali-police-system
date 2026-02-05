<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    protected $fillable = ['user_id', 'station_id', 'facility_id', 'duty_type', 'shift', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
