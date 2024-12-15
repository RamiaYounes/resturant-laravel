<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'status',
        'total_price',
        'date_of_delivery',
        
           
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function orderItem(){   
        return $this->hasMany(OrderItem::class, 'order_id');
    } 
    public function user(){   
        return $this->belongsTo(User::class, 'user_id');
    } 
    
}
