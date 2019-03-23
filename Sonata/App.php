<?php

namespace Sonata;


use Sonata\Common\Config;
use Sonata\Http\Request;
use Sonata\Http\Response;
use Sonata\Mvc\View;

class App
{
    private static $instance = null;

    private $request = null;

    private $response = null;

    private $view = null;

    private $config = null;

    private $pdo = null;

    private $services = [];

    public function getService($name)
    {
        if (! isset($this->services[$name])) {
            $className = '\\services\\' . $this->getCanonicalName($name) . 'Service';
            $this->services[$name] = new $className();
        }
        return $this->services[$name];

    }

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

    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = new Config();
        }
        return $this->config;
    }

    public function getPdo()
    {
        if ($this->pdo === null) {
            $config = $this->getConfig()->get('db');
            $pdo_driver = $config['pdo_driver'];
            $host = $config['host'];
            $port = $config['port'];
            $dbname = $config['dbname'];
            $user = $config['user'];
            $password = $config['password'];


            $dsn = "{$pdo_driver}:host={$host};port={$port};dbname={$dbname}";
            $this->pdo = new \PDO(
                $dsn,
                $user,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC]
            );
        }
        return $this->pdo;
    }

    public function run()
    {
        session_start();
        ob_start();
        $controller = $this->getRequest()->getController();
        $action = $this->getRequest()->getAction();

        $controllerClassName = '\\controllers\\' . $this->getCanonicalName($controller) . 'Controller';
        $actionName = 'action' . $this->getCanonicalName($action);

        $controllerClass = new $controllerClassName();
        $controllerClass->init();

        $this->getResponse()->setContent($controllerClass->{$actionName}());
        $this->getResponse()->flush();

        ob_end_flush();
    }

    private function __construct()
    {
    }

}