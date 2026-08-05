<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    protected $fillable = ['faq_category_id', 'question', 'answer'];

    public function faqCategory()
    {
        return $this->belongsTo(FaqCategory::class);
    }
}
