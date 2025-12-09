<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'interests', 'active'];

    protected $casts = [
        'interests' => 'array',
        'active' => 'boolean',
    ];

    public function courtBookings()
    {
        return $this->hasMany(CourtBooking::class);
    }
}
