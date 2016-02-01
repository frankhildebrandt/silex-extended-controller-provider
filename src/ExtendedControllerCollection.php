<?php
namespace FHildebrandt\Silex\ExtendedController;

use Closure;
use Silex\ControllerCollection;

/**
 * {@inheritDoc}
 */
class ExtendedControllerCollection extends ControllerCollection
{
    /**
     * @param string $mountPoint
     * @param Closure $func
     */
    public function declareSubRoute($mountPoint, $func)
    {
        $controllerCollection = $this->factorNewCollection();
        $func($controllerCollection);
        $this->mount($mountPoint, $controllerCollection);
    }

    /**
     * Magic Method for Custom - HTTP-Methods
     * @param $method
     * @param $arguments
     * @return $this|\Silex\Controller
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->defaultRoute, $method)) {
            call_user_func_array(array($this->defaultRoute, $method), $arguments);

            foreach ($this->controllers as $controller) {
                call_user_func_array(array($controller, $method), $arguments);
            }

            return $this;
        }

        $this->match($arguments[0], $arguments[1])->method(strtoupper($method));
        return $this->post($arguments[0]."/".strtolower($method), $arguments[1]);
    }


    /**
     * Returns a new ControllerCollection
     * @return ControllerCollection
     */
    protected function factorNewCollection()
    {
        return new ExtendedControllerCollection($this->defaultRoute, $this->routesFactory);
    }

}