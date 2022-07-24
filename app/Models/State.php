<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Location::class);
    }
}
