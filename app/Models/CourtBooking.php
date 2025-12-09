<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtBooking extends Model
{
    protected $fillable = ['court_id', 'member_id', 'start_time', 'end_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
