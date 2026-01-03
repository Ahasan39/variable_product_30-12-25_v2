<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
   protected $fillable = ['guest_name', 'guest_email', 'content', 'is_read'];
}
