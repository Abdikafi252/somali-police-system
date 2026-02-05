<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crime extends Model
{
    protected $fillable = ['case_number', 'crime_type', 'severity', 'description', 'location', 'crime_date', 'reported_by', 'status'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function policeCases()
    {
        return $this->hasMany(PoliceCase::class);
    }

    // Alias for policeCases (singular)
    public function case()
    {
        return $this->hasOne(PoliceCase::class);
    }

    public function suspects()
    {
        return $this->hasMany(Suspect::class);
    }

    public function victims()
    {
        return $this->hasMany(Victim::class);
    }

    public function arrests()
    {
        return $this->hasMany(Arrest::class);
    }

    public function evidence()
    {
        return $this->hasMany(Evidence::class);
    }
}
