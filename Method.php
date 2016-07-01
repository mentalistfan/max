<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    protected $fillable = ['title', 'description', 'name', 'seotitle', 'seodescription', 'seokeywords'];

    public function mfos()
    {
        return $this->belongsToMany('App\Mfo', 'methods_mfos');
    }

    public function histories()
    {
        return $this->belongsToMany('App\Mfo', 'methods_histories');
    }

}
