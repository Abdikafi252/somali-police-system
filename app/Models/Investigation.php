<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    protected $fillable = ['case_id', 'findings', 'evidence_list', 'outcome', 'status', 'files'];

    protected $casts = [
        'files' => 'array'
    ];

    public function policeCase()
    {
        return $this->belongsTo(PoliceCase::class, 'case_id');
    }

    public function statements()
    {
        return $this->hasMany(InvestigationStatement::class);
    }
}
