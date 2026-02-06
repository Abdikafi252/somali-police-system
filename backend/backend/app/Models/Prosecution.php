<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prosecution extends Model
{
    protected $fillable = ['case_id', 'prosecutor_id', 'court_id', 'submission_date', 'charges', 'status'];

    public function policeCase()
    {
        return $this->belongsTo(PoliceCase::class, 'case_id');
    }

    // Alias for policeCase
    public function case()
    {
        return $this->belongsTo(PoliceCase::class, 'case_id');
    }

    public function prosecutor()
    {
        return $this->belongsTo(User::class, 'prosecutor_id');
    }

    public function court()
    {
        return $this->belongsTo(Facility::class, 'court_id');
    }

    public function courtCase()
    {
        return $this->hasOne(CourtCase::class);
    }

    public function suspect()
    {
        return $this->hasOneThrough(Suspect::class, PoliceCase::class, 'id', 'crime_id', 'case_id', 'crime_id');
    }
}
