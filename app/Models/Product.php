<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getRouteKeyName() {
        return 'slug';
    }
    
    public function image()
    {
        // Returns first image only
        return $this->hasOne(Productimage::class, 'product_id')->select('id', 'image', 'product_id','price');
    }
    
    public function images()
    {
        // Returns all images
        return $this->hasMany(Productimage::class, 'product_id')->select('id', 'image', 'product_id','price');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->select('id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id', 'name');
    }
    
    public function subcategory(){
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id')
            ->select('id', 'subcategoryName');
    }
    

    public function childcategory()
    {
        return $this->belongsTo(Childcategory::class,'childcategory_id','id')->select('id','childcategoryName','slug');
    }
    
    public function brand()
    {
        return $this->hasOne(Brand::class,'id','brand_id')->select('id','name','slug');
    }
    
    public function productimages()
    {
        return $this->hasMany(Productimage::class, 'product_id');
    }
    public function sizes()
    {
        return $this->belongsToMany('App\Models\Size','productsizes')->withTimestamps();
    }
    public function colors()
    {
        return $this->belongsToMany('App\Models\Color','productcolors')->withTimestamps();
    }
    
    public function prosizes()
    {
        return $this->hasMany('App\Models\Productsize');
    }
    public function procolors()
    {
        return $this->hasMany('App\Models\Productcolor');
    }
    
     public function prosize()
    {
        return $this->hasOne(Productsize::class, 'product_id');
    }
     public function procolor()
    {
        return $this->hasOne(Productcolor::class, 'product_id');
    }
     public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
