<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 24.03.19
 * Time: 0:54
 */

namespace Sonata\Mvc;


abstract class ModelAbstract
{
    protected $data = [];

    protected $dataOld = [];

    abstract public function getTable();

    abstract public function getKey();

    public function get($name)
    {
        $result = null;
        if (array_key_exists($name, $this->data)) {
            $result = $this->data[$name];
        } elseif (array_key_exists($name, $this->dataOld)) {
            $result = $this->dataOld[$name];
        }
    }


    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function getData()
    {
        return $this->data;
    }

    public function init($data = [])
    {
        $this->dataOld = $data;
    }

    public function __construct($data = [])
    {
        $this->init($data);
    }
}