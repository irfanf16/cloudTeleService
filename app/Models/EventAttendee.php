<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EventAttendee extends Model
{
    use HasFactory, HasUuids;


    protected $fillable = [
        'id',
        'fname',
        'lname',
        'phone',
        'email',
        // 'self',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
