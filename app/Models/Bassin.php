<?php

namespace App\Models;

use Vinelab\NeoEloquent\Eloquent\Model;

class Bassin extends Model
{
    protected $label = 'Bassin';

    protected $fillable = [
        'nomBassin', 'superficieKm2', 'partSurfaceNationale'
    ];
}

