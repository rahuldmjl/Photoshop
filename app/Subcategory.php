<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    
    public function getMaincategory()
    {
        return $this->hasOne('App\category','entity_id','maincategoryname'); 
    }

    public static function validation($name)
    {
        return Subcategory::where('subcatname',$name)->exists();
    }
}
