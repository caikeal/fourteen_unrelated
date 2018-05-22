<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'store_id'      => 'integer',
        'item_id'       => 'integer',
        'quantity'      => 'integer',
    ];

    protected $fillable = [
        'store_id',
        'order_no',
        'item_id',
        'item_type',
        'item_title',
        'image_url',
        'quantity',
        'unit_price',
        'sub_total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_no', 'order_no');
    }
}
