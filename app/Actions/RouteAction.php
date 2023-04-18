<?php

namespace App\Actions;

use Lorisleiva\Actions\Action;

abstract class RouteAction extends Action
{
    /**
     * Check if action is called from a route.
     *
     * @return bool
     */
    public function isFromRoute(): bool
    {
        return (bool) request()->route()?->getName();
    }
}
