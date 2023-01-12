<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\PointsRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function showAllCustomers() // nao tou a dar load aqui tbm acho eu
    {
        $customers = User::where('type','C')->paginate(20);
        return UserResource::collection($customers);
    }

    public function showCustomer($id)
    {
        $user = User::where('type','C')->findOrFail($id);
        return new UserResource($user);
    }

    public function showCustomersByEmail($email)
    {
        $query = DB::table('users')->where('email','LIKE','%'.$email.'%')->where('type','C')->paginate(20);
        return UserResource::collection($query);
    }


    public function signUpCustomer(CustomerRequest $request)
    {
        try{
            DB::beginTransaction();

            //dd($request);

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->type = "C";
            $user->blocked = 0;
            $user->photo_url = $request->input('photo_url');
            $user->save(); // save user to create an id for user_id

            $customer = new Customer();
            $customer->phone = $request->input('phone');
            $customer->points = 0;
            $customer->nif = $request->input('nif');
            $customer->default_payment_type = $request->input('default_payment_type');
            $customer->default_payment_reference = $request->input('default_payment_reference');
        
            $user->customer()->save($customer);

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new UserResource($user);
    }

    public function editCustomerProfile(CustomerRequest $request, $id)
    {
        try{
            DB::beginTransaction();

            $user = User::where('type','C')->findOrFail( $request->id );

            // change fields
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->type = $request->input('type');
            $user->blocked = $request->input('blocked');
            $user->photo_url = $request->input('photo_url');

            $user->save();

            $customer = $user->customer;
            $customer->phone = $request->input('phone');
            $customer->points = $request->input('points');
            $customer->nif = $request->input('nif');
            $customer->default_payment_type = $request->input('default_payment_type');
            $customer->default_payment_reference = $request->input('default_payment_reference');
        
            $user->customer()->save($customer);

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new UserResource($user);
    }

    public function blockCustomer(CustomerRequest $request)
    {
        $user = User::where('type','C')->findOrFail( $request->id );
        $user->blocked = 1;

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function unblockCustomer(CustomerRequest $request)
    {
        $user = User::where('type','C')->findOrFail( $request->id );
        $user->blocked = 0;

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function deleteCustomerAccount($id)
    {
        $user = User::where('type','C')->findOrFail( $id );
        if( $user->delete() ){
            return new UserResource( $user );
        }
    }

    public function addPointsCustomer(PointsRequest $request,$user_id)
    {
        try {
            $customer = Customer::where('user_id',$user_id)->first();
            //var_dump($customer);
            $customer->points = $customer->points + $request->points;
    
            $customer->save();
    
            $user = User::where('type','C')->findOrFail( $user_id );
        } catch (\Throwable $error) {
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }
        return new UserResource( $user );

    }

    public function removePointsCustomer(PointsRequest $request,$user_id)
    {
        try {
            $customer = Customer::where('user_id',$user_id)->first();
            //var_dump($customer);
            $customer->points = $customer->points - $request->points;
    
            $customer->save();
    
            $user = User::where('type','C')->findOrFail( $user_id );
        } catch (\Throwable $error) {
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }
        return new UserResource( $user );

    }
}
