<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
        'avatar_url',
    ];

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
        ];
    }

    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function getAvatarUrlAttribute($value)
    {
        if (!$value) return null;

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (strpos($value, 'storage/') === 0) {
            return url($value);
        }

        return url('storage/' . $value);
    }

    public function setAvatarUrlAttribute($value)
    {
        if (!$value) {
            $this->attributes['avatar_url'] = null;
            return;
        }

        if (strpos($value, url('/')) === 0) {
            $this->attributes['avatar_url'] = str_replace(url('/') . '/', '', $value);
            return;
        }

        $this->attributes['avatar_url'] = $value;
    }
}
