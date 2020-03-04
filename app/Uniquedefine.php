<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\category;
use App\photography_product;
use App\Subcategory;
class Uniquedefine extends Model
{
  
    public function getcategory()
    {
        return $this->hasOne('App\category','entity_id','category_id');
    }
    public function getproduct()
    {
        return $this->hasOne('App\photography_product','id','product_id');
    }
    public function getsubcategory()
    {
        return $this->hasOne('App\Subcategory','id','sub_category_id');
    }

    public static function checkproduct($sku)
    {

        return Uniquedefine::where('sku','=',$sku)->exists();
    }
    public static function getUniqueProduct($sku)
    {
        return Uniquedefine::where('sku','=',$sku)->first();
    }

    
}
