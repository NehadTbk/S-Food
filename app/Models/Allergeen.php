<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergeen extends Model
{
    protected $fillable = ['name'];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menuitem_allergeen');
    }
}
