<?php

namespace RedMoon\TinkerServer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RedMoon\TinkerServer\Skeleton\SkeletonClass
 */
class TinkerServerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tinker-server';
    }
}
