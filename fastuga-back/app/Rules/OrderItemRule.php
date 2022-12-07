<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

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

        // foreach de cada item do pedido
        foreach ($value as $item){
            // verificar se o item tem product_id
            if( is_null($item->product_id) ) return false;
            
            // verificar se o produto existe
            $product = Product::find( $item->product_id );
            if($product == null) return false;

            $orderItem->product_id = $item->product_id;
            $orderItem->notes =  !is_null($item->notes) ? $item->notes : null;
    
        }

        // se correr tudo até aqui sem entrar em nenhum if
        return true;
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
