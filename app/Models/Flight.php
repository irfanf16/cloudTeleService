<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['id', 'name', 'email', 'phone', 'location', 'destination', 'class', 'terms'];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'flight_id', 'id');
    }
}
