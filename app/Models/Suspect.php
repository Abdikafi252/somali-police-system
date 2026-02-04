<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspect extends Model
{
    protected $fillable = ['name', 'nickname', 'age', 'mother_name', 'gender', 'residence', 'national_id', 'crime_id', 'arrest_status', 'photo'];

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }

    public function arrests()
    {
        return $this->hasMany(Arrest::class);
    }
}
