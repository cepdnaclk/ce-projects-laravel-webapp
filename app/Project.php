<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // protected $guarded = [];

    public function getCategories()
    {
        return $this->belongsToMany(Category::class);
    }


    public static function getBrowserLink($title)
    {
        // TODO: need to checkfor duplicates

        return preg_replace('/\W+/', '-', ($title));
    }

}
