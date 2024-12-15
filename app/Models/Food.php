<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'image',
        'price',
        'detail',
        "category_id",
       
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    public function category(){   
        return $this->belongsTo(Category::class, 'category_id');
    } 
}
