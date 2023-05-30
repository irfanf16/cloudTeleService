<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\Google\GoogleCalendarService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'ref_id',
        'timezone',
        'sync_token',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function confirmedEvents()
    {
        return $this->events()->where('status', config('calendar.event.statuses')[0]);
    }

    /**
     * is time slot available
     *
     * @param  string $start
     * @param  string $end
     * @return bool
     */
    public function isSlotAvailable(
        string $start,
        string $end
    ): bool {
        return !$this->confirmedEvents()
            ->where('start', Carbon::parse($start)->setTimezone(config('app.timezone')))
            ->where('end', Carbon::parse($end)->setTimezone(config('app.timezone')))
            ->exists();
    }
}
