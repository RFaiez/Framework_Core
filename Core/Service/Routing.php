<?php

namespace rfaiez\framework_core\Service;

use DI\Container;

class Routing
{
    private $container;

    /**
     * Constructor.
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Set controller name and method to execute.
     *
     * @param string $controller
     * @param string $action
     *
     * @return array
     */
    public function call(string $controller, string $action = '__invoke'): array
    {
        return [$this->container->get($controller), $action];
    }

    /**
     * Redirect to last visited route.
     *
     * @return void
     */
    public function redirectBack(): void
    {
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
}
