<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'avatar_original',
        'provider',
        'provider_id',
        'provider_token',
        'teacher_id',
        'group_id',
    ];

    // Legacy constants - use UserRole enum or helper methods instead
    const ROLE_ADMIN = 'admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin->value;
    }

    public function isTeacher(): bool
    {
        return $this->role === UserRole::Teacher->value;
    }

    public function isStudent(): bool
    {
        return $this->role === UserRole::Student->value;
    }

    public function isPrivileged(): bool
    {
        return in_array($this->role, [UserRole::Admin->value, UserRole::Teacher->value]);
    }

    public function getRoleEnum(): UserRole
    {
        return UserRole::from($this->role);
    }

    /**
     * Get the user's full name (backward compatibility accessor).
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

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

    public function ds()
    {
        return $this->hasMany(DS::class);
    }

    public function correctionRequests()
    {
        return $this->hasMany(CorrectionRequest::class);
    }

    public function exercisesSheets()
    {
        return $this->hasMany(ExercisesSheet::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quizze::class, 'student_id');
    }
    
    public function exerciseWhitelist()
    {
        return $this->hasMany(ExerciseWhitelist::class);
    }
    
    public function whitelistedExercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_whitelist');
    }
    
    public function whitelistRequests()
    {
        return $this->hasMany(WhitelistRequest::class);
    }
    
    public function processedWhitelistRequests()
    {
        return $this->hasMany(WhitelistRequest::class, 'processed_by');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(User::class, 'teacher_id');
    }

    public function group()
    {
        return $this->belongsTo(StudentGroup::class, 'group_id');
    }
}
