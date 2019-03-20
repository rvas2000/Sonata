<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 20.03.19
 * Time: 17:38
 */

namespace Sonata\Http;


class Request
{
    protected $module = 'default';

    protected $controller = 'default';

    protected $action = 'index';

    protected $parameters = [];

    protected $hasModule = false;

    public function __construct()
    {
        $this->parseUri();
        $this->loadParameters();
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    protected function parseUri()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $script = (explode('/', $_SERVER['SCRIPT_FILENAME']));
        $elements = array_reverse(
            array_values(
                array_filter(
                    array_map(
                        function ($v) {return preg_replace('/\?.+$/', '', $v); },
                        array_diff($uri, $script)
                    ),
                    function ($v) {return ! empty($v);}
                )
            )
        );

        $this->hasModule = false;
        if (($element = array_pop($elements)) !== null) {
            if (realpath(BASE_PATH . '/Site/' . $element) !== false) {
                $this->hasModule = true;
                $this->module = $element;
            } else {
                $this->controller = $element;
            }
        }

        if (($element = array_pop($elements)) !== null) {
            if ($this->hasModule) {
                $this->controller = $element;
            } else {
                $this->action = $element;
            }
        }

        if ($this->hasModule && ($element = array_pop($elements)) !== null) {
            $this->action = $element;
        }


        $elements = array_reverse($elements);
        $name = null;
        $value = null;
        $isName = true;
        foreach ($elements as $element) {
            if ($isName) {
                $name = $element;
            } else {
                $value = $element;
            }

            if ( ! (empty($name) || empty($value)) ) {
                $_GET[$name] = $value;
            }
            $isName = ! $isName;
        }
    }


    protected function loadParameters()
    {
        if (isset($_POST)) {
            foreach ($_POST as $name => $value) {
                $this->parameters[$name] = $value;
            }
        }

        if (isset($_GET)) {
            foreach ($_GET as $name => $value) {
                $this->parameters[$name] = $value;
            }
        }
    }

    public function getParameter($name, $defaultValue = null)
    {
        $result = $defaultValue;
        if (isset($this->parameters[$name])) {
            $result = $this->parameters[$name];
        }
        return $result;
    }



}