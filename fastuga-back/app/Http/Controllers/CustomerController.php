<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->user_id = $request->input('user_id');
        $customer->phone = $request->input('phone');
        $customer->points = $request->input('points');
        $customer->nif = $request->input('nif');
        $customer->default_payment_type = $request->input('default_payment_type');
        $customer->default_payment_reference = $request->input('default_payment_reference');

        if( $customer->save() ){
            return $customer;
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
