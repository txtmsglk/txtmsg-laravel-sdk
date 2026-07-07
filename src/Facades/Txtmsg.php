<?php

namespace Txtmsg\SmsClient\Facades;

use Illuminate\Support\Facades\Facade;

class Txtmsg extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'txtmsg';
    }
}
