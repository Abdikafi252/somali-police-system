<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Victim extends Model
{
    protected $fillable = ['crime_id', 'name', 'age', 'gender', 'injury_type', 'contact_info'];

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }
}
