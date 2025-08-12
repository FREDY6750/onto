<?php

namespace App\Models;

use Vinelab\NeoEloquent\Eloquent\Model;

class CoursEau extends Model
{
    protected $label = 'CoursEau';

    protected $fillable = [
        'patronymeCoursEau',
        'longueurKmCoursEau',
        'debitMoyenCoursEau',
        'navigableCoursEau',
        'usageEau'
    ];

    public function source()
    {
        return $this->hasOne(Source::class, 'aPourSource');
    }

    public function bassin()
    {
        return $this->hasMany(Bassin::class, 'aPourBassin');
    }
}

