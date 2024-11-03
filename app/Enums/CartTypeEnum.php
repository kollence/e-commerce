<?php

namespace App\Enums;

enum CartTypeEnum: string
{
    case DEFAULT_CART = 'default_cart';
    case FOR_LATER_CART = 'for_later_cart';
}
