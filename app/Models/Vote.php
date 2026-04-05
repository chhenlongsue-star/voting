<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'poll_id', 'option_id'];

    // Optional: Add a relationship to the Option to show results later
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}