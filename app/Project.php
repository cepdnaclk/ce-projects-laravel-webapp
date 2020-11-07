<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // protected $guarded = [];

    public function getCategories(){
        return $this->belongsToMany(Category::class);
    }

}
