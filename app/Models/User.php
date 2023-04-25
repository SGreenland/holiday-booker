<?php

namespace App\Models;

use App\Models\Team;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'team_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany("App\Models\Role");
    }

    public function hasAnyRole($role)
    {

        return null !== $this->roles->where("name", $role)->first();
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function cancellationRequests() {
        return $this->hasMany(CancellationRequest::class);
    }
}
