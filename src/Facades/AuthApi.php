<?php

namespace WandsDev\AuthApi\Facades;

use Illuminate\Support\Facades\Facade;

class AuthApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'auth-api';
    }
}
