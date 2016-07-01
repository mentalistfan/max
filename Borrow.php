<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = ['title', 'description', 'name', 'seotitle', 'seodescription', 'seokeywords'];

    public function mfos()
    {
        return $this->belongsToMany('App\Mfo', 'borrows_mfos');
    }
}
