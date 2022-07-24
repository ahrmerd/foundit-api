<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    static private $statuses = ['active' => 1, 'disabled' => 0, 'promoted' => 2];

    static public function STATUSES()
    {
        return self::$statuses;
    }
    public function scopeActive(Builder $query)
    {
        $query->where('status', $this->statuses['active']);
    }

    public function scopeDisabled(Builder $query)
    {
        $query->where('status', $this->statuses['disabled']);
    }

    public function scopePromoted(Builder $query)
    {
        $query->where('status', $this->statuses['promoted']);
    }
}
