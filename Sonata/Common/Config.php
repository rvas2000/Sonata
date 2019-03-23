<?php
/**
 * Created by PhpStorm.
 * User: basil
 * Date: 23.03.19
 * Time: 0:20
 */

namespace Sonata\Common;


class Config
{
    protected $data = [];

    public function __construct()
    {
        $files = array_filter(
            scandir(CONFIG_PATH, SORT_ASC),
            function ($v) { return preg_match('/\\.conf$/', $v);}
        );

        foreach ($files as $file) {
            $element = parse_ini_file(CONFIG_PATH . '/' . $file, true);
            $this->data = array_merge($this->data, $element);
        }
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function getAll()
    {
        return $this->data;
    }
}