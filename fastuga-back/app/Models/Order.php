<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_number',
        'status',
        'customer_id',
        'total_price',
        'total_paid',
        'total_paid_with_points',
        'points_gained',
        'points_used_to_pay',
        'payment_type',
        'payment_reference',
        'date',
        'delivered_by',
    ];

    protected $nullable = [ 
        'ticket_number', // pq no inicio quando a order é criada, nao se consegue calcular o ticket number de imediato
        'customer_id',
        'delivered_by' 
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
