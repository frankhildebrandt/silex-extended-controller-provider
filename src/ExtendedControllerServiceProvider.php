<?php
namespace FHildebrandt\Silex\ExtendedController;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ExtendedControllerServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['controllers_factory'] = $pimple->factory(function ($pimple) {
            return new ExtendedControllerCollection($pimple['route_factory'], $pimple['routes_factory']);
        });
    }
}