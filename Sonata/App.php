<?php

namespace Sonata;

use Sonata\Http\Request;

class App
{
    private static $instance = null;

    private $request = null;

    public function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     *  Преобразует имя в camelCase
     */
    public function getCanonicalName($name): string
    {
        $nameParts = explode('-', $name);
        return implode('', array_map(function ($v) {return ucfirst(strtolower($v));}, $nameParts));
    }


    public function run()
    {
        $controllerClass = '\\controllers\\' . $this->getCanonicalName($this->getRequest()->getController()) . 'Controller';
        $actionName = 'action' . $this->getCanonicalName($this->getRequest()->getAction());
        $controller = new $controllerClass();
        $controllerClass->{$actionName}();
    }


    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}