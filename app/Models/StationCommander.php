<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationCommander extends Model
{
    protected $fillable = [
        'user_id',
        'station_id',
        'rank',
        'appointed_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function officers()
    {
        return $this->hasMany(StationOfficer::class, 'commander_id');
    }
}
