<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const TYPE = ['other' => 1, 'bug' => 2, 'user' => 3, 'technical' => 4];

    const STATUS = ['new' => 1, 'processing' => 2, 'processed' => 3, 'dismissed' => 4];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeAttribute($value)
    {
        return array_flip($this::TYPE)[$value];
    }

    public function getStatusAttribute($value)
    {
        return array_flip($this::STATUS)[$value];
    }
}
