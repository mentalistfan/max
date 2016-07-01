<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class City extends Model implements SluggableInterface
{
    protected $fillable = ['title', 'slug', 'abr', 'name', 'seotitle', 'seodescription', 'seokeywords'];

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function mfos()
    {
        return $this->belongsToMany('App\Mfo', 'cities_mfos');
    }
}
