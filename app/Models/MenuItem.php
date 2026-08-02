<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'short_description',
        'full_description',
        'ingredients',
        'price',
        'photo',
        'type',
        'available_on',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'available_on' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function allergeens()
    {
        return $this->belongsToMany(Allergeen::class, 'menuitem_allergeen');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
