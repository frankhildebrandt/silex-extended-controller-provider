<?php
namespace FHildebrandt\Silex\ExtendedController;

use ArrayAccess;
use Pimple\Container;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

abstract class ExtendedControllerProvider implements ControllerProviderInterface, ArrayAccess
{
    private $_application;

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->_application[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->_application[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->_application[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->_application[$offset]);
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $this->_application = $app;
        $this->declareController($app);
        $controllerCollection = $this->factorControllerCollection();
        $this->declareRoutes($controllerCollection);
        return $controllerCollection;
    }

    abstract protected function declareController(Container $pimple);

    /**
     * Returns a new ControllerCollection
     * @return ExtendedControllerCollection
     */
    protected function factorControllerCollection()
    {
        return $this->_application['controllers_factory'];
    }

    abstract protected function declareRoutes(ExtendedControllerCollection $controllerCollection);

    /**
     * Returns the current Application
     * @return mixed
     */
    public function getApplication()
    {
        return $this->_application;
    }
}