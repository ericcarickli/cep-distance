<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CEPDistance extends Model
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
