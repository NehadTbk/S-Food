<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = ['content', 'image'];

    public static function current(): self
    {
        return static::firstOrCreate([], ['content' => '']);
    }
}
