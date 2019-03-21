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

    public function getView()
    {
        return $this->getApp()->getView();
    }


}