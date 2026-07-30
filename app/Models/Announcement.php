<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'posted_by'];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
