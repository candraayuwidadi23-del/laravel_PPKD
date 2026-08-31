<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'order_qty',
        'order_price',
        'order_subtotal'
    ];
    // 1 to 1
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
