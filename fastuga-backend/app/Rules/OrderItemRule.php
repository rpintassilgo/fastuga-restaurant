<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class OrderItemRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        if($value == null || !request()->has('order_items')){
            $this->error = "No items in the order";

            return false;
        }

        //var_dump($value);
        //dd(is_array($value));

        /*
        $collection = collect($value)->map(function ($item) {
            if( is_null($item['product_id']) ) return false;

            $product = Product::find( $item['product_id'] );
            if($product == null) return false;
        })->reject(function ($name) {
            return false;
        });*/

        
        //$dataArr = (array) $value;

        // foreach de cada item do pedido
        foreach ($value as $item){
            // verificar se o item tem product_id
            if( is_null($item['product_id']) ) return false;
            
            // verificar se o produto existe
            $product = Product::find( $item['product_id'] );
            if($product == null) return false;
    
        }
        
        // se correr tudo até aqui sem entrar em nenhum if
        return true;
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
