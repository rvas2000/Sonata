<?php
if (! defined('SONATA_PATH')) define("SONATA_PATH", realpath(__DIR__ . '/..'));
if (! defined('BASE_PATH')) define("BASE_PATH", realpath(__DIR__ . '/..'));

spl_autoload_register(function ($className) {
    $path = false;
    $elements = explode('\\', $className);

    if (count($elements) > 1) {
        if ($elements[0] == 'Sonata') {
            $path = realpath(SONATA_PATH . '/' . implode('/', $elements) . '.php');
        } else {
            $path = realpath(BASE_PATH . '/Site/' . implode('/', $elements) . '.php');
        }
    }


    if ($path !== false) {require_once $path;}
});