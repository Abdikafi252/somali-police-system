<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidence';

    protected $fillable = [
        'crime_id',
        'file_path',
        'file_type',
        'description',
    ];

    public function crime()
    {
        return $this->belongsTo(Crime::class);
    }
}
