<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['author', 'email', 'text', 'mfo_id', 'rating', 'show', 'data', 'plus', 'minus'];

    public function mfo()
    {
    	return $this->belongsTo('App\Mfo');
    }
}
