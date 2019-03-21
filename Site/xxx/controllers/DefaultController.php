<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 13:55
 */

namespace xxx\controllers;

use Sonata\Http\Response;
use Sonata\Mvc\ControllerAbstract;


class DefaultController extends ControllerAbstract
{
    public function init()
    {
        parent::init();
        $this->getResponse()->setType(Response::TYPE_TEXT);
    }

    public function actionIndex()
    {
        echo 'aaaaa';
    }
}