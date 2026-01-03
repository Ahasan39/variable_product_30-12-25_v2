<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncompleteOrder extends Model
{
    use HasFactory;

    protected $table = 'incomplete_orders';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'session_id',
        'phone',
        'name',
        'ip_address',
        'cart_data',
        'total',
        'status',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'cart_data' => 'array',
        'total'     => 'decimal:2',
    ];

    /**
     * Default attributes
     */
    protected $attributes = [
        'status' => 'incomplete',
    ];

    /**
     * Scopes
     */
    public function scopeIncomplete($query)
    {
        return $query->where('status', 'incomplete');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Helpers
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsIncomplete()
    {
        $this->update(['status' => 'incomplete']);
    }

    /**
     * Check if phone already exists recently
     */
    public static function recentPhoneExists($phone, $hours = 12)
    {
        return self::where('phone', $phone)
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }
}
