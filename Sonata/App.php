<?php

namespace Sonata;


use Sonata\Http\Request;
use Sonata\Http\Response;
use Sonata\Mvc\View;

class App
{
    private static $instance = null;

    private $request = null;

    private $response = null;

    private $view = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *  Преобразует имя в camelCase
     */
    public function getCanonicalName($name)
    {
        $nameParts = explode('-', $name);
        return implode('', array_map(function ($v) {return ucfirst(strtolower($v));}, $nameParts));
    }

    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = new Request();
        }
        return $this->request;
    }

    public function getResponse()
    {
        if ($this->response === null) {
            $this->response = new Response();
        }
        return $this->response;
    }

    public function getView()
    {
        if ($this->view === null) {
            $this->view = new View();
        }
        return $this->view;
    }

    public function run()
    {
        $module = $this->getRequest()->getModule();
        $controller = $this->getRequest()->getController();
        $action = $this->getRequest()->getAction();

        $controllerClassName = ($module === 'default' ? '\\controllers\\' : '\\' . $module . '\\controllers\\') . $this->getCanonicalName($controller) . 'Controller';
        $actionName = 'action' . $this->getCanonicalName($action);

        $controllerClass = new $controllerClassName();
        $controllerClass->{$actionName}();

        echo $controllerClassName;
    }

    private function __construct()
    {
    }

}