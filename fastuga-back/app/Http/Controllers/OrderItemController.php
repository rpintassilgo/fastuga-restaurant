<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;

class OrderItemController extends Controller
{
    public function showOrderItems() // logo se vê, são 100 mil :)
    {
        return OrderItemResource::collection()->paginate(20);
    }

    public function showHotDishesByStatus($status)
    {
        $orderItems = null;
        switch ($status) {
            case 'waiting':
                $matchThese = ['status' => 'W', 'type' => 'hot dish'];
                $orderItems = OrderItemResource::collection(OrderItem::where($matchThese)->paginate(20));
                break;
            case 'preparing':
                $matchThese = ['status' => 'P', 'type' => 'hot dish'];
                $orderItems = OrderItemResource::collection(OrderItem::where($matchThese)->paginate(20));
                break;
            case 'ready':
                $matchThese = ['status' => 'R', 'type' => 'hot dish'];
                $orderItems = OrderItemResource::collection(OrderItem::where($matchThese)->paginate(20));
                break;
            default:
                return response()->json(['message' => 'Invalid orderItem status!'],400);
                break;
        }
        return OrderItemResource::collection($orderItems);
    }
}
