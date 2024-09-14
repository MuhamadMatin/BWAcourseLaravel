<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use function Termwind\parse;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'occupation',
        'avatar',
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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    public function subscribe_transactions()
    {
        return $this->hasMany(SubscribeTransaction::class);
    }

    public function hasActiveSubscription()
    {
        $lastestSubscription = $this->subscribe_transactions()
            ->where('is_paid', true)
            ->latest('updated_at')
            ->first();

        if (!$lastestSubscription) {
            return false;
        }

        $subscriptionEndDate = Carbon::parse($lastestSubscription->subscribe_start_date)->addMonth(1);
        return Carbon::now()->lessThanOrEqualTo($subscriptionEndDate);
    }
}