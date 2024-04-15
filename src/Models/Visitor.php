<?php

namespace Izpixel\LaravelTracker\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Izpixel\LaravelTracker\Traits\EncryptableDbAttribute;
use Izpixel\LaravelTracker\Traits\WithHelpers;

class Visitor extends Model
{
    use HasFactory, HasUuids, WithHelpers, EncryptableDbAttribute;

    protected $guarded = [];

    protected $casts = [
        'queries' => 'array',
        'languages' => 'array',
        'steps' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = $this->getTableName();
    }
}
