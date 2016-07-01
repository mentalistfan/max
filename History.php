<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class History extends Model implements SluggableInterface
{
    protected $fillable = ['title', 'slug', 'seotitle', 'seodescription', 'seokeywords', 'price', 'logo', 'desc', 'how', 'time', 'see', 'action', 'actiondesc', 'site'];

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function methods()
    {
        return $this->belongsToMany('App\Method', 'methods_histories');
    }
}
