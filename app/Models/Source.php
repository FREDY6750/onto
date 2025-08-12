<?php

namespace App\Models;

use Vinelab\NeoEloquent\Eloquent\Model;

class Source extends Model
{
    protected $label = 'Source';

    protected $fillable = [
        'nomSource', 'typeSource', 'localiteSource', 'debitSource'
    ];
}
