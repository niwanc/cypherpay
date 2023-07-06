<?php

namespace Cyphergarden\Cypherpay;

use Illuminate\Support\Facades\Facade;

class CypherpayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cypherpay';
    }
}
