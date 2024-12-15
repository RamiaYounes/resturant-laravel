<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
       
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function food(){

        return $this->hasMany(Food::class, 'category_id');
      
    } 
}
