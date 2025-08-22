<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dispatcher_id',
        'delivery_id',
        'invoice',
        'total',
        'phone',
        'address',
        'status',
        'payment_id',
    ];

    const STATUS = ['Procesando', 'En Camino', 'Entregado', 'No Entregado'];

    protected $casts = [
        'total' => 'int',
        // 'images' => 'array',
    ];

    /**
     * Get the user that owns the Order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the dispatcher that owns the Order
     */
    public function dispatcher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatcher_id', 'id');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_id', 'id');
    }
}
