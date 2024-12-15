<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'date_of_delivery',           
    ];

}
