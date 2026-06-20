<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role_id'
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
    public function chatThreads()
    {
        return $this->hasMany(ChatThread::class, 'user_id');
    }

    public function assignedChatThreads()
    {
        return $this->hasMany(ChatThread::class, 'admin_id');
    }

    public function sentChatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function isAdmin(): bool
    {
        if ((int) $this->role_id === 1) {
            return true;
        }

        try {
            if ($this->role_id && \Illuminate\Support\Facades\Schema::hasTable('roles')) {
                $roleName = \Illuminate\Support\Facades\DB::table('roles')
                    ->where('id', $this->role_id)
                    ->value('name');

                return in_array(strtolower((string) $roleName), [
                    'admin',
                    'administrator',
                    'super admin',
                ], true);
            }
        } catch (\Throwable $exception) {
            return false;
        }

        return false;
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route('customer.password_reset', [
            'token' => $token,
            'email' => $this->email,
        ]);

        $this->notify(new ResetPasswordNotification($url));
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
