<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'ref_id',
        'calendar_id',
        'description',
        'summary',
        'hangoutLink',
        'services',
        'start',
        'end',
        'timezone',
        'status',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(EventAttendee::class);
    }

    public function guests()
    {
        return $this->belongsToMany(EventAttendee::class)->where('email', '<>', config('calendar.admin.email'));
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'event_id', 'id');
    }
}
