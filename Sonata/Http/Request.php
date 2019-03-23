<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 12:33
 */

namespace Sonata\Http;


class Request
{

    protected $controller = null;

    protected $action = null;

    protected $parameters = null;

    protected $rootPath = null;

    public function getController()
    {
        if ($this->controller === null) {
            $this->parseUri();
        }
        return $this->controller;
    }

    public function getAction()
    {
        if ($this->action === null) {
            $this->parseUri();
        }
        return $this->action;
    }

    public function getRootPath()
    {
        if ($this->rootPath === null) {
            $this->parseUri();
        }
        return $this->rootPath;
    }

    public function getParameter($name, $defaultValue = null)
    {
        $result = $defaultValue;

        if ($this->parameters === null) {
            $this->loadParameters();
        }

        if (isset($this->parameters[$name])) {
            $result = $this->parameters[$name];
        }

        return $result;
    }

    protected function parseUri()
    {
        $uriElements = array_diff(
            explode('/', $_SERVER['REQUEST_URI']),
            explode('/', $_SERVER['SCRIPT_NAME'])
        );

        $this->rootPath = '/' . implode('/', array_filter(
            array_intersect(
                explode('/', $_SERVER['REQUEST_URI']),
                explode('/', $_SERVER['SCRIPT_NAME'])
            ),
            function ($v) { return ! (empty($v) || preg_match('/\\.php$/', $v));}
        ));

        // Разбиваем строку запроса на элементы
        $uriElements = array_filter(

            array_map(

                function ($v) {return preg_replace('/\\?.+$/', '', $v);}, // убираем GET-параметры из строки запроса

                // Это действие нужно в том случае, когда скрипт index.php не в корне сайта: отделяем путь к скрипту от полной строки запроса
                array_diff(
                    explode('/', $_SERVER['REQUEST_URI']),
                    explode('/', $_SERVER['SCRIPT_NAME'])
                )
            ),

            function ($v) { return ! empty($v);} // Убираем из вывода пустые элементы
        );

        $uriElements = array_reverse($uriElements);

        $this->controller = 'default';
        $this->action = 'index';

        if (count($uriElements)) {

            // Первый элемент - контроллер
            if (($element = array_pop($uriElements)) !== null) {
                $this->controller = $element;
            }

            // Второй элемент - действие
            if (($element = array_pop($uriElements)) !== null) {
                $this->action = $element;
            }

            // Оставшиеся элементы переносим в GET-параметры
            $uriElements = array_reverse($uriElements);
            $name = null;
            $value = null;
            $isName = true;
            foreach ($uriElements as $element) {
                if ($isName) {
                    // Если это имя параметра
                    $name = $element;
                } else {
                    // Если это значение параметра
                    $value = $element;

                    if (! (empty($name) || empty($value) || is_numeric($name))) {
                        $_GET[$name] = $value;
                        $name = null;
                        $value = null;
                    }
                }

                $isName = ! $isName;
            }

        }

    }

    protected function loadParameters()
    {
        $this->parameters = [];
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
}