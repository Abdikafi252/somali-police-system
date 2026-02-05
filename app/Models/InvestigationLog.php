<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationLog extends Model
{
    protected $fillable = [
        'case_id',
        'user_id',
        'log_entry',
        'log_type',
        'entry_date'
    ];

    protected $casts = [
        'entry_date' => 'datetime'
    ];

    public function policeCase()
    {
        return $this->belongsTo(PoliceCase::class, 'case_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
