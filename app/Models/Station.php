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

    // Accessor for officer count (Merges New System + Legacy Users)
    public function getOfficerCountAttribute()
    {
        // 1. Count Active Officers from StationOfficer table
        $newSystemCount = $this->activeStationOfficers()->count();

        // 2. Count Legacy Users (directly assigned via users.station_id)
        // We need to avoid double counting if a user is in both
        $legacyCount = $this->users()->whereDoesntHave('stationOfficer', function ($q) {
            $q->where('station_id', $this->id)->where('status', 'active');
        })->count();

        return $newSystemCount + $legacyCount;
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
}
