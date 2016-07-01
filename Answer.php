<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Answer extends Model implements SluggableInterface
{
    protected $fillable = ['question', 'slug', 'seodescription', 'seokeywords', 'answer', 'data', 'seotitle', 'title'];

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'question',
        'save_to'    => 'slug',
    ];

    public function articles()
    {
    	return $this->belongsToMany('App\Article', 'answers_articles');
    }

    public function news()
    {
    	return $this->belongsToMany('App\News', 'answers_newses');
    }

    public function mfos()
    {
    	return $this->belongsToMany('App\Mfo', 'answers_mfos');
    }
}
