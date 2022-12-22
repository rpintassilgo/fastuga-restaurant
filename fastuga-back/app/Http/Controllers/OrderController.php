<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function showAllOrders()
    {
        return OrderResource::collection(Order::with('orderItems.product')->paginate(20));
    }

    public function showStatusOrders($status)
    {
        $orders = null;
        switch ($status) {
            case 'preparing':
                $orders = Order::with('orderItems.product')->where('status','P')->paginate(20);
                break;
            case 'ready':
                $orders = OrderResource::collection(Order::with('orderItems.product')->where('status','R')->paginate(20));
                break;
            case 'delivered':
                $orders = Order::with('orderItems.product')->where('status','D')->paginate(20);
                break;
            case 'cancelled':
                $orders = Order::with('orderItems.product')->where('status','C')->paginate(20);
                break;
            default:
                return response()->json(['message' => 'Invalid order status!'],400);
                break;
        }
        return OrderResource::collection($orders);
    }

    public function showStatusOrdersFromCustomer($id,$status)
    {
        $orders = null;
        switch ($status) {
            case 'preparing':
                $matchThese = ['status' => 'P', 'customer_id' => $id];
                $orders = Order::with('orderItems.product')->where($matchThese)->paginate(20);
                break;
            case 'ready':
                $matchThese = ['status' => 'R', 'customer_id' => $id];
                $orders = Order::with('orderItems.product')->where($matchThese)->paginate(20);
                break;
            case 'delivered':
                $matchThese = ['status' => 'D', 'customer_id' => $id];
                $orders = Order::with('orderItems.product')->where($matchThese)->paginate(20);
                break;
            case 'cancelled':
                $matchThese = ['status' => 'C', 'customer_id' => $id];
                $orders = Order::with('orderItems.product')->where($matchThese)->paginate(20);
                break;
            default:
                return response()->json(['message' => 'Invalid order status!'],400);
                break;
        }
        return OrderResource::collection($orders);
    }


    public function showAllOrdersFromCustomer($id)
    {
        $orders = Order::with('orderItems.product')->where('customer_id',$id)->paginate(20);
        return OrderResource::collection($orders);
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
            //var_dump('ola');
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

                //$orderItem->order_id = 
                $orderItem->order_local_number = $orderLocalNumber;
                $orderLocalNumber = $orderLocalNumber + 1;
                
                // all products were verified inside orderItemRule (however if it fails, it will enter "catch")
                $product = Product::findOrFail( $item['product_id'] );

                $orderItem->product_id = $item['product_id'];
                $orderItem->status = $product->type == "hot dish" ? "W" : "R"; // the inicial status of an item order is Waiting
                $orderItem->price = $product->price;
                $orderItem->notes =  array_key_exists('notes',$item) ? $item['notes'] : null;

                $totalPrice = $totalPrice + $product->price;

                //var_dump($orderItem);

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
            //var_dump(gettype($orderItemsToSave));
            $order->orderItems()->saveMany($orderItemsToSave);


            $order->save();

            //var_dump($order);
            
            /*
            foreach($orderItemsToSave as $orderItem){
                $order->orderItems()->save($orderItem);
            }
            */
            

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
        if (Auth::user()->type != "EC"){
            return response()->json(['message' => 'The current logged user is not an employee chef'],400);
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
        if (Auth::user()->type != "ED"){
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
        if (Auth::user()->type != "EM") {
            return response()->json(['message' => 'The current logged user is not an employee manager'], 400);
        }

        // dinheiro deve ser reembolsado (como é que vais reembolsar o dinheiro ???)

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->status = "C"; // Cancelled

            // os pontos gastos e ganhos devem ser reembolsados (isto é só dar um update em alguns campos do user)
            $user_id = Auth::id(); // user autenticado

            $customer = Customer::findOrFail($user_id); // obter o customer
            $customer->points = $customer->points + $order->points_used_to_pay; // devolve os pontos gastos na order que vai cancelar
            $customer->points = $customer->points - $order->points_gained; // retira os pontos ganhos com a order que vai ser cancelada

            $customer->save();
            $order->save();

            DB::commit();
        } catch (\Throwable $error) {
            DB::rollback();
            return response()->json(['message' => 'Internal server error', 'error' => $error->getMessage()], 500);
        }

        return new OrderResource($order);
    }

}
