<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PaymentReferenceRule implements Rule
{

    private $error = 'Payment reference validation failed';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //dd($attribute);
        if($value == null && !request()->has('default_payment_type') && !request()->has('payment_type')){
            $this->error = "No Payment reference or type";

            return false;
        }

        $paymentType = request()->has('default_payment_type') ? 
                       request()->get('default_payment_type') : request()->get('payment_type');

       // dd($paymentType);

        switch($paymentType){
            case('VISA'):
                if(mb_strlen($value) != 16){
                    $this->error = 'Digit length must be 16 for visa';
                    return false;
                }
                break;
            case('MBWAY'):
                if(mb_strlen($value) != 9){
                    $this->error = 'Digit length must be 9 for mbway';
                    return false;
                }
                break;
            case('PAYPAL'):
                if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->error = 'Invalid email';
                    return false;
                }
                break;
            default:
                return false;
        }

        // check if the first digit is 0
        if(strcmp(substr($value,0),"0") == 0){
            $this->error = 'First digit cannot be 0';
            return false;
        }

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}
