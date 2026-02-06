<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationOfficer extends Model
{
    protected $fillable = [
        'officer_id',
        'station_id',
        'commander_id',
        'rank',
        'duty_type',
        'assigned_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function commander()
    {
        return $this->belongsTo(StationCommander::class, 'commander_id');
    }
}
