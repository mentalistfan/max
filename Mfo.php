<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Mfo extends Model implements SluggableInterface
{

	protected $fillable = ['title', 'slug', 'seodescription', 'seokeywords', 'seotitle', 'banner', 'video', 'howtake', 'terms', 'advantages', 'redemption', 'logo', 'site', 'document', 'registerdata', 'rating', 'mintime', 'maxtime', 'summa', 'minpercent', 'maxpercent', 'srok', 'take', 'city', 'see', 'transfer', 'wrong', 'desc', 'action', 'actiondesc'];

    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function comments()
    {
    	return $this->hasMany('App\Comment');
    }

    public function answers()
    {
        return $this->belongsToMany('App\Answer', 'answers_mfos');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article', 'articles_mfos');
    }

    public function newses()
    {
        return $this->belongsToMany('App\News', 'newses_mfos');
    }

    public function methods()
    {
        return $this->belongsToMany('App\Method', 'methods_mfos');
    }

    public function cities()
    {
        return $this->belongsToMany('App\City', 'cities_mfos');
    }

    public function setts()
    {
        return $this->belongsToMany('App\Sett', 'setts_mfos');
    }

    public function borrows()
    {
        return $this->belongsToMany('App\Borrow', 'borrows_mfos');
    }
}
