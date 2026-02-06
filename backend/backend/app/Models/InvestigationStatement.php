<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationStatement extends Model
{
    protected $fillable = ['investigation_id', 'person_name', 'person_type', 'statement', 'statement_date'];

    public function investigation()
    {
        return $this->belongsTo(Investigation::class);
    }
}
