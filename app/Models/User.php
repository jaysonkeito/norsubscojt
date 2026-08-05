<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'role', 'status',
        'company_id', 'job_role', 'job_role_other',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function coordinatorProfile()
    {
        return $this->hasOne(CoordinatorProfile::class);
    }

    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function studentsAdvised()
    {
        return $this->hasMany(Student::class, 'coordinator_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCoordinator(): bool
    {
        return $this->role === 'coordinator';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isCompany(): bool
    {
        return $this->role === 'company';
    }

    public function isDean(): bool
    {
        return $this->role === 'dean';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Approval hierarchy:
     * - Admin can approve/reject anyone (Dean, Coordinator, Company).
     * - Dean can approve/reject Coordinator and Company accounts only —
     *   not other Dean accounts (those are Admin's call).
     */
    public function canApprove(User $target): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isDean()) {
            return in_array($target->role, ['coordinator', 'company']);
        }

        return false;
    }
}
