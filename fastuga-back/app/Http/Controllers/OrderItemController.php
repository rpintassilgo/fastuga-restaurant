<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{

    // isto aqui está tudo mal, o order item nao tem tipo, tenho de arranjar maneira de aceder ao produto da order item e assim verificar o tipo do produto
    // possivelmente nao é necessario dar hardcode, deve dar para fazer isso atraves das relacoes

    public function showOrderItems() // logo se vê, são 100 mil :)
    {
        return OrderItemResource::collection(OrderItem::paginate(20));
    }

    public function showHotDishes()
    {
        return OrderItemResource::collection(OrderItem::with('product')
        ->whereRelation('product','type','hot dish')->paginate(20));
    }

    public function showHotDishesByStatus($status)
    {
        $orderItems = null;
        switch ($status) {
            case 'waiting':
                $orderItems = OrderItem::with('product'/*,'chef'*/)
                ->whereRelation('product','type','hot dish')
                ->where('status','W')->paginate(20);
                break;
            case 'preparing':
                $orderItems = OrderItem::with('product'/*,'chef'*/)
                ->whereRelation('product','type','hot dish')
                ->where('status','P')->paginate(20);
                break;
            case 'ready':
                $orderItems = OrderItem::with('product'/*,'chef'*/)
                ->whereRelation('product','type','hot dish')
                ->where('status','R')->paginate(20);   // por alguma razao nao ta a mostrar o paginate
                break;
            default:
                return response()->json(['message' => 'Invalid orderItem status!'],400);
                break;
        }
        return OrderItemResource::collection($orderItems);
    }

    
    public function changeStatusOrderItem($id,$status)
    {
        
        if ( Auth::user()->type != "EC"){
            return response()->json(['message' => 'The current logged user is not an employee chef'],400);
        }

        try{
            DB::beginTransaction();

            $orderItem = OrderItem::findOrFail($id);
            switch ($status) {
                case 'waiting':
                    $orderItem->status = "W"; // Waiting
                    break;
                case 'preparing':
                    $orderItem->status = "P"; // Preparing
                    break;
                case 'ready':
                    $orderItem->status = "R"; // Ready
                    break;
                default:
                    return response()->json(['message' => 'Invalid orderItem status!'],400);
                    break;
            }

            $orderItem->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new OrderItemResource($orderItem);
    }
}
