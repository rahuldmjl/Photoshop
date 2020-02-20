<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class photographyUploadFile extends Model
{
    
    public static function getFile()
    {
        return photographyUploadFile::all();

    }

    public function getUserid()
    {
        return $this->hasOne('App\user','id','userid');
    }
}
