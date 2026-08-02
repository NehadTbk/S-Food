<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    protected $fillable = ['name'];

    public function faqItems()
    {
        return $this->hasMany(FaqItem::class);
    }
}
