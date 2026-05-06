<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyLog query()
 * @method static int count()
 */
class EmergencyLog extends Model
{
    protected $fillable = ['payload', 'reason'];

    protected $casts = [
        'payload' => 'array',
    ];
}
