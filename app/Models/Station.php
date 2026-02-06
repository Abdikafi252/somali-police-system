<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['station_name', 'location', 'commander_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function commander()
    {
        return $this->belongsTo(User::class, 'commander_id');
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    // Accessor for officer count (Based on Users table)
    public function getOfficerCountAttribute()
    {
        return $this->users()->count();
    }

    // Relationship to Station Commanders
    public function commanders()
    {
        return $this->hasMany(StationCommander::class);
    }

    // Relationship to Station Officers (History & Active)
    public function stationOfficers()
    {
        return $this->hasMany(StationOfficer::class);
    }

    // Active Station Officers
    public function activeStationOfficers()
    {
        return $this->hasMany(StationOfficer::class)->where('status', 'active');
    }

    // Active Station Commander (The single active record)
    public function activeStationCommander()
    {
        return $this->hasOne(StationCommander::class)->where('status', 'active')->latest();
    }

    // Accessor to get the User object of the active commander
    public function getCurrentCommanderAttribute()
    {
        return $this->activeStationCommander ? $this->activeStationCommander->user : $this->commander;
    }
}
