<?php

namespace App\Enums;

enum UserTypeEnum: int
{
    case SELLER = 0;
    case CUSTOMER = 1;
    case CONTACT = 2;
    case ADMIN = 3;

    public static function getValue(string $name){
        return constant("self::$name")->value;
    }
}
