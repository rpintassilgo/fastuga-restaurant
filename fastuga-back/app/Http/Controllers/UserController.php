<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Http\Resources\UserResource;
use App\Http\Resources\CustomerResource;
use App\Controllers\CustomerController;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function showCustomer($id)
    {
        $user = User::where('type','C')->findOrFail($id);
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->type = $request->input('type');
        $user->blocked = $request->input('blocked');
        $user->photo_url = $request->input('photo_url');

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function storeCustomer(Request $request)
    {
        try{
            DB::beginTransaction();

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->type = $request->input('type');
            $user->blocked = $request->input('blocked');
            $user->photo_url = $request->input('photo_url');
            $user->save(); // save user to create an id for user_id

            $customer = new Customer();
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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail( $request->id );
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->type = $request->input('type');
        $user->blocked = $request->input('blocked');
        $user->photo_url = $request->input('photo_url');

        if( $user->save() ){
            return new UserResource($user);
        }
    }

    public function updateCustomer(Request $request, $id)
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

    public function destroy($id)
    {
        $user = User::findOrFail( $id );
        if( $user->delete() ){
            return new UserResource( $user );
        }
    }

    public function destroyCustomer($id)
    {
        $user = User::where('type','C')->findOrFail( $id );
        if( $user->delete() ){
            return new UserResource( $user );
        }
    }
}
