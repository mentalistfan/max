<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class News extends Model implements SluggableInterface
{
    protected $fillable = ['title', 'slug', 'seotitle', 'seodescription', 'seokeywords', 'intro', 'content', 'banner', 'mfo', 'answers_id', 'data', 'see'];
    protected $table = 'newses';

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function answers()
    {
    	return $this->belongsToMany('App\Answer', 'answers_newses');
    }

    public function mfos()
    {
        return $this->belongsToMany('App\Mfo', 'newses_mfos');
    }
}
