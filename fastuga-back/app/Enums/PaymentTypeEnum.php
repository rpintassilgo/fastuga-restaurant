<?php

namespace App\Enums;

enum PaymentTypeEnum: string{
    case VISA = 'visa';
    case MBWAY = 'mbway';
    case PAYPAL = 'paypal';
}
