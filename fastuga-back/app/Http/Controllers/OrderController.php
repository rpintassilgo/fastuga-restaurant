<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function showAllOrders()
    {
        return OrderResource::collection(Order::paginate(20));
    }

    public function showAllOrdersFromCustomer($id)
    {
        $order = Order::with('orderItems.product')->where('customer_id',$id)->get();
        return OrderResource::collection($order);
    }

    public function showOrder($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return new OrderResource($order);
    }

    public function createOrder(OrderRequest $request)
    {

        //dd($request);

        // check if logged user is customer or if it is not logged (customer without account) 
        // isto devia ser middleware depois e secalhar toda a gente pode criar order
        /*
        if (Auth::user()->user_type != "C" || !Auth::check()){
            return response()->json(['message' => 'The user is not a customer'],400);
        }
        */

        try{
            DB::beginTransaction();

            $order = new Order; 

            // get ticket number from previous order
            $previousTicketNumber = Order::find(DB::table('orders')->max('id'))->ticket_number;
            //dd($previousTicketNumber);

            $ticketNumber = null;
            if($previousTicketNumber == 99){
                $ticketNumber = 1;
            } else{
                $ticketNumber = ($previousTicketNumber) + 1;
            }

            // criar itens do pedido e adicioná-los ao pedido
            $items = $request->input('order_items');
            $orderLocalNumber = 1;
            $totalPrice = 0;
            $orderItemsToSave = [];
            foreach ($items as $item){
                $orderItem = new OrderItem();

                $orderItem->order_local_number = $orderLocalNumber;
                $orderLocalNumber = $orderLocalNumber + 1;
                
                // all products were verified inside orderItemRule (however if it fails, it will enter "catch")
                $product = Product::findOrFail( $item['product_id'] );

                $orderItem->product_id = $item['product_id'];
                $orderItem->status = "W"; // the inicial status of an item order is Waiting
                $orderItem->price = $product->price;
                $orderItem->notes =  array_key_exists('notes',$item) ? $item['notes'] : null;

                $totalPrice = $totalPrice + $product->price;

                array_push($orderItemsToSave,$orderItem); // we cant save it rn, because there is no order_id
            }


            $order->status = "P";
            $order->ticket_number = $ticketNumber;
            $order->customer_id = $request->has('customer_id') ? $request->input('customer_id') : null; //
            $order->total_price = $totalPrice;
            $order->total_paid = $request->input('total_paid');
            $order->total_paid_with_points = $request->input('total_paid_with_points');
            $order->points_gained = $request->input('points_gained');
            $order->points_used_to_pay = $request->input('points_used_to_pay');
            $order->payment_type = $request->input('payment_type');
            $order->payment_reference = $request->input('payment_reference');
            $order->date = Carbon::now()->format('Y-m-d');
            $order->delivered_by = null;


            $order->save(); // save to create id for order_id
            
            foreach($orderItemsToSave as $orderItem){
                $order->orderItems()->save($orderItem);
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
