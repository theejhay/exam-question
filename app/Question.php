<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'category_id', 'option_a', 'option_b', 'option_c', 'option_d', 'answer'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
