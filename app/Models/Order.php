<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'deliverer_id',
        'chosen_date',
        'chosen_time',
        'delivery_type',
        'street',
        'house_number',
        'bus',
        'postal_code',
        'city',
        'status',
        'payment_method',
        'paid_at',
        'delivery_cost',
        'total',
        'payment_token',
    ];

    protected function casts(): array
    {
        return [
            'chosen_date' => 'date',
            'paid_at' => 'datetime',
            'delivery_cost' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliverer()
    {
        return $this->belongsTo(User::class, 'deliverer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
