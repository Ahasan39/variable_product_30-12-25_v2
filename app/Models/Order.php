<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderdetails() 
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(OrderDetails::class, 'id', 'order_id')->select('id','order_id','product_id');
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status');
    }
    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function order_status()
{
    // Adjust foreign key if needed (here assuming 'order_status' is the FK column name)
    return $this->belongsTo(OrderStatus::class, 'order_status');
}
}
