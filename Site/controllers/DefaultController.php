<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 21.03.19
 * Time: 13:55
 */

namespace controllers;

use Sonata\Http\Response;
use Sonata\Mvc\ControllerAbstract;


class DefaultController extends ControllerAbstract
{

    public function actionIndex()
    {
        $complexes = $this->getService('db')->getComplexes();
        return $this->render(['complexes' => $complexes]);
    }

    public function actionContacts()
    {
        return $this->render();
    }

    public function actionPages()
    {
        return $this->render();
    }

}