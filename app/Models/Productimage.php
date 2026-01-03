<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productimage extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'color', 'size', 'price', 'image'];
 public function product(){
     return $this->hasOne(Product::class,'product_id','id')->select('image','size','price','color');
 }
}

