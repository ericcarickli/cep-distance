<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CEPDistance extends Eloquent
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
