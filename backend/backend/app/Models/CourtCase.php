<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    protected $fillable = ['prosecution_id', 'judge_id', 'hearing_date', 'verdict', 'status'];

    public function prosecution()
    {
        return $this->belongsTo(Prosecution::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
