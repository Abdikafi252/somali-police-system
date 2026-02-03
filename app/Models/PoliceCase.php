<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliceCase extends Model
{
    protected $table = 'cases';

    protected $fillable = ['crime_id', 'case_number', 'assigned_to', 'status'];

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function investigation()
    {
        return $this->hasOne(Investigation::class, 'case_id');
    }

    public function prosecution()
    {
        return $this->hasOne(Prosecution::class, 'case_id');
    }

    public function logs()
    {
        return $this->hasMany(InvestigationLog::class, 'case_id');
    }
}
