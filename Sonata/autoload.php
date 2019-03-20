<?php
if (! defined('BASE_PATH')) define('BASE_PATH', realpath(__DIR__ . '/..'));
if (! defined('SONATA_PATH')) define('SONATA_PATH', __DIR__ . '/..');

spl_autoload_register(function ($className) {
    $elements = explode('\\', $className);
    $path = false;

    if (count($elements) > 1) {
        if ($elements[0] === 'Sonata') {
             if ($elements[1] !== 'App') {$elements[0] .= '/Lib';}

            $path = realpath (SONATA_PATH . '/' . implode('/', $elements) . '.php');

        } else {
            $path = realpath(BASE_PATH . '/Site/' . implode('/', $elements) . '.php');
        }

    } else {
        $path = realpath(BASE_PATH . '/Site/modules/' . implode('/', $elements) . '.php');
    }


    if ($path !== false) require_once $path;
});
