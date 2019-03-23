<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 14:26
 */

namespace Sonata\Mvc;


use Sonata\App;

class View
{

    protected $js = [];

    protected $css = [];

    public function getApp()
    {
        return App::getInstance();
    }

    public function getRootPath()
    {
        return $this->getApp()->getRequest()->getRootPath();
    }

    public function getTitle()
    {
        return 'Заголовок';
    }

    public function registerCss($css)
    {
        $this->css[$css] = $css;
    }

    public function registerJs($js)
    {
        $this->css[$js] = $js;
    }

    public function renderCss()
    {
        $rootPath = $this->getRootPath();
        return implode('', array_map(function ($v) use ($rootPath) { return '<link rel="stylesheet" href="' . $rootPath . $v . '"/>';}, $this->css));
    }

    public function renderJs()
    {
        $rootPath = $this->getRootPath();
        return implode('', array_map(function ($v) use ($rootPath) { return '<script type="text/javascript" src="' . $rootPath . $v . '"></script>';}, $this->js));
    }

    public function renderPartial($data = [], $template = null)
    {
        ob_start();
        extract($data);
        include $this->getTemplatePath($template);
        return ob_get_clean();
    }

    public function render($data = [], $template = null)
    {
        ob_start();
        extract($data);
        $this->registerCss('/www/css/main.css');
        $this->registerJs('/www/js/jquery-3.3.1.min.js');
        $this->registerJs('/www/js/main.js');
        $TEMPLATE_PATH = $this->getTemplatePath($template);
        include $this->getLayoutPath();
        return ob_get_clean();
    }

    protected function getLayoutPath()
    {
        $layout = $this->getApp()->getConfig()->get('main')['layout'];
        return BASE_PATH . '/Site/layouts/' . $layout . '.php';
    }

    protected function getTemplatePath($template = null)
    {
        return BASE_PATH
            . '/Site/views/'
            . $this->getApp()->getRequest()->getController() . '/'
            . ($template == null ? $this->getApp()->getRequest()->getAction() : $template) . '.php';
    }

}