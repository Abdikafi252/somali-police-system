<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'station_id',
        'region_id',
        'status',
        'rank',
        'appointed_date',
        'profile_image',
        'last_seen_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function crimes()
    {
        return $this->hasMany(\App\Models\Crime::class, 'reported_by');
    }

    public function cases()
    {
        return $this->hasMany(\App\Models\PoliceCase::class, 'assigned_to');
    }

    public function deployments()
    {
        return $this->hasMany(\App\Models\Deployment::class);
    }

    public function commander()
    {
        return $this->hasOne(StationCommander::class, 'user_id');
    }

    public function stationOfficer()
    {
        return $this->hasOne(StationOfficer::class, 'user_id');
    }

    public function stationOfficers()
    {
        return $this->hasMany(StationOfficer::class, 'user_id');
    }

    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 5;
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }
}
