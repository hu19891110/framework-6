<?php

namespace Assely\Routing;

use Assely\Routing\CallableDispatcher;
use Assely\Routing\ControllerDispatcher;
use Closure;

trait ControllerActionResolver
{
    /**
     * Route controller.
     *
     * @var \Assely\Routing\Controller
     */
    protected $controller;

    /**
     * Route action callback.
     *
     * @var string|callable
     */
    protected $action;

    /**
     * Run route.
     *
     * @return mixed
     */
    public function run()
    {
        if ($this->getAction() instanceof Closure) {
            return $this->runCallable();
        }

        return $this->runController();
    }

    /**
     * Run controller method action.
     *
     * @return mixed
     */
    public function runController()
    {
        return (new ControllerDispatcher($this->container))->dispatch(
            $this, $this->getController(), $this->getControllerMethod()
        );
    }

    /**
     * Run callable router action.
     *
     * @return mixed
     */
    public function runCallable()
    {
        return (new CallableDispatcher($this->container))->dispatch(
            $this, $this->getAction()
        );
    }

    /**
     * Gets the Route controller.
     *
     * @return \Assely\Routing\Controller
     */
    public function getController()
    {
        list($controller) = explode('@', $this->getAction());

        if ( ! $this->controller) {
            $this->setController($this->makeController($controller));
        }

        return $this->controller;
    }

    /**
     * Sets the Route controller.
     *
     * @param \Assely\Routing\Controller $controller
     *
     * @return self
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Gets the Route controller method.
     *
     * @return string
     */
    public function getControllerMethod()
    {
        return explode('@', $this->getAction())[1];
    }

    /**
     * Makes controller instance.
     *
     * @throws \Assely\Routing\RoutingException
     *
     * @return \Assely\Routing\Controller
     */
    public function makeController($controller)
    {
        $namespace = $this->router->getNamespace();

        $classname = "{$namespace}\\{$controller}";

        if (class_exists($classname)) {
            return $this->container->make($classname);
        }

        throw new RoutingException("Controller [{$classname}] do not exists.");
    }

    /**
     * Gets the value of action.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }
}
