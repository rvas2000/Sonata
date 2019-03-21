<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 14:23
 */

namespace Sonata\Mvc;


use Sonata\App;

abstract class ControllerAbstract
{
    public function getApp()
    {
        return App::getInstance();
    }

    public function getRequest()
    {
        return $this->getApp()->getRequest();
    }

    public function getResponse()
    {
        return $this->getApp()->getResponse();
    }

    public function getView()
    {
        return $this->getApp()->getView();
    }

    public function render($template, $data = [])
    {
        return $this->getView()->render($template, $data);
    }

    public function renderPartial($template, $data = [])
    {
        return $this->getView()->renderPartial($template, $data);
    }

    public function init()
    {

    }

}