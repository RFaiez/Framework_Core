<?php

namespace Service;

use DI\Container;

class Routing{

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function call(string $controller, string $action="__invoke")
    {
        return [$this->container->get($controller), $action];
    }

    public function redirectBack()
    {
        return header('Location: ' . $_SERVER['HTTP_REFERER']);     
    }

}