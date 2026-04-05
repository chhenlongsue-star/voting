<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Ensure both name and slug are fillable
    protected $fillable = ['name', 'slug'];

    /**
     * Relationship: A Category has many Polls
     */
    public function polls()
    {
        return $this->hasMany(Poll::class);
    }
}