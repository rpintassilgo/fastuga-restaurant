<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;

class OrderItemController extends Controller
{

    // isto aqui está tudo mal, o order item nao tem tipo, tenho de arranjar maneira de aceder ao produto da order item e assim verificar o tipo do produto
    // possivelmente nao é necessario dar hardcode, deve dar para fazer isso atraves das relacoes

    public function showOrderItems() // logo se vê, são 100 mil :)
    {
        $orderItems = OrderItem::where('type','C')->paginate(20);
        return OrderItemResource::collection($orderItems);
    }

    public function showHotDishes()
    {
        return OrderItemResource::collection(OrderItem::where('type','hot dish')->paginate(20));
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
