<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'table_name', 'record_id', 'details', 'description', 'old_values', 'new_values'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
