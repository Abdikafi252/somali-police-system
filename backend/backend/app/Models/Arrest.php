<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrest extends Model
{
    protected $fillable = ['suspect_id', 'crime_id', 'officer_id', 'arrest_date', 'location', 'reason'];

    public function suspect()
    {
        return $this->belongsTo(Suspect::class);
    }

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
