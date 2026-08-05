<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'mobile_number', 'office_landline', 'id_badge_number',
        'alternate_email', 'photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Company rep profile counts as "complete" once a Mobile Number is on
     * file — that's the one field Admin/Dean genuinely need to reach them.
     * Everything else here is optional.
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->mobile_number);
    }
}
