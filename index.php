<?php
//phpinfo();

use Sonata\App;

//header ("Content-Type: text/plain");
ini_set('display_errors', 1);
require_once 'bootstrap.php';

App::getInstance()->run();