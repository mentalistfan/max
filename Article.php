<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Article extends Model implements SluggableInterface
{
    protected $fillable = ['title', 'slug', 'seodescription', 'seokeywords', 'seotitle', 'intro', 'content', 'banner', 'data', 'see'];

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function answers()
    {
    	return $this->belongsToMany('App\Answer', 'answers_articles');
    }

    public function mfos()
    {
        return $this->belongsToMany('App\Mfo', 'articles_mfos');
    }
}
