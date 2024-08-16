<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Distance extends Eloquent
{
    protected $collection = 'cep_distances';

    protected $fillable = [
        'cep_from',
        'cep_to',
        'calculated_distance',
        'created_at',
        'updated_at',
    ];
}
