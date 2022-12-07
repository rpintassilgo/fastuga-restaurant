<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;
use App\Models\Product;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{

    public function showAllOrders()
    {
        return Order::all();
    }

    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        return new OrderResource($order);
    }

    public function createOrder(OrderRequest $request)
    {

        // check if logged user is customer or if it is not logged (customer without account)
        if (Auth::user()->user_type != "C" || !Auth::check()){
            return response()->json(['message' => 'The user is not a customer'],400);
        }

        try{
            DB::beginTransaction();

            $order = new Order;
            //$order->ticket_number = $request->input('t'); // o ticket number gira de 1 a 99; ver como fazer isto mais tarde

            $order->status = "P";
            $order->customer_id = $request->has('customer_id') ? $request->input('customer_id') : null; //
            $order->total_price = $request->input('total_price');
            $order->total_paid = $request->input('total_paid');
            $order->total_paid_with_points = $request->input('total_paid_with_points');
            $order->points_gained = $request->input('points_gained');
            $order->points_used_to_pay = $request->input('points_used_to_pay');
            $order->payment_type = $request->input('payment_type');
            $order->payment_reference = $request->input('payment_reference');
            $order->date = now()->toDateTimeString('Y-m-d');
            $order->delivery_by = null;

            $order->save(); // save order to create an "id for order_id" and "id to check the ticket number from the order with id-1"

            // get ticket number from previous order
            $previousOrder = Order::findOrFail($order->id-1);
            $ticketNumber = null;
            if($previousOrder->ticket_number == 99){
                $ticketNumber = 1;
            } else{
                $ticketNumber = ($previousOrder->ticket_number) + 1;
            }

            $order->ticket_number = $ticketNumber;

            $order->save(); // save again to save ticket_number

            
            // criar itens do pedido e adicioná-los ao pedido
            $items = $request->input('order_items');
            $orderLocalNumber = 1;
            foreach ($item as $items){
                $orderItem = new OrderItem();

                $orderItem->order_local_number = $orderLocalNumber;
                $orderLocalNumber = $orderLocalNumber + 1;
                
                // all products were verified inside orderItemRule (however if it fails, it will enter "catch")
                $product = Product::findOrFail( $item->product_id );

                $orderItem->product_id = $item->product_id;
                $orderItem->status = "W"; // the inicial status of an item order is Waiting
                $orderItem->price = $product->price;
                $orderItem->notes =  !is_null($item->notes) ? $item->notes : null;
        
                $order->orderItem()->save($orderItem);
            }
    

            DB::commit();

        } catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new OrderResource($order);

    }

    public function setOrderToReady($id)
    {
        // sera q é o ED q diz q tá ready? se sim, verificar se o user logado é do tipo ED
        if (Auth::user()->user_type != "ED"){
            return response()->json(['message' => 'The current logged user is not an employee delivery'],400);
        }

        try{
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->status = "R"; // Ready

            $order->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new OrderResource($order);
    }

    public function deliverOrder($id)
    {
        // verificar se o user logado é do tipo ED
        if (Auth::user()->user_type != "ED"){
            return response()->json(['message' => 'The current logged user is not an employee delivery'],400);
        }

        try{
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->status = "D"; // Delivered

            $order->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new OrderResource($order);
    }

    public function cancelOrder($id)
    {
        // quem cancela? gerente
        if (Auth::user()->user_type != "EM"){
            return response()->json(['message' => 'The current logged user is not an employee manager'],400);
        }

        // os pontos gastos e ganhos devem ser reembolsados (isto é só dar um update em alguns campos do user)
        // dinheiro deve ser reembolsado (como é que vais reembolsar o dinheiro ???)

        try{
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->status = "C"; // Cancelled

            $order->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new OrderResource($order);
    }

}
