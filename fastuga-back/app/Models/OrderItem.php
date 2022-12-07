<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'order_local_number',
        'product_id',
        'status',
        'price',
        'preparation_by',
        'notes'
    ];

    protected $nullable = [ 
        'order_id',
        'notes',
        'preparation_by'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
