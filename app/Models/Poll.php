<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    // Add 'category_id' to this array
    protected $fillable = ['question', 'is_active', 'category_id'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}