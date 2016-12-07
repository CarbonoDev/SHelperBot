<?php
namespace App\GoogleCustomSearch\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleCSE extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'google_cse';
    }
}
