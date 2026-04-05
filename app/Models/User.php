<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role', 
        'is_admin', 
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

    /**
     * The "booted" method of the model.
     * This handles the automatic synchronization of roles.
     */
    protected static function booted()
    {
        // "saving" covers both creating NEW users and updating OLD users
        static::saving(function ($user) {
            // We use trim() to prevent issues with hidden spaces in the database
            $currentRole = trim(strtolower($user->role));

            if ($currentRole === 'admin' || $currentRole === 'sub_admin') {
                $user->is_admin = 1;
            } else {
                $user->is_admin = 0;
            }
        });
    }

    /**
     * Helper methods for your Blade templates
     */
    public function isAdmin(): bool
    {
        return $this->is_admin == 1 || $this->role === 'admin';
    }

    public function isSubAdmin(): bool
    {
        return $this->role === 'sub admin';
    }
}