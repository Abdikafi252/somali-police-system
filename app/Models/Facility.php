<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = ['name', 'type', 'location', 'security_level', 'commander_id'];

    public function commander()
    {
        return $this->belongsTo(User::class, 'commander_id');
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function prosecutions()
    {
        return $this->hasMany(Prosecution::class, 'court_id');
    }
}
