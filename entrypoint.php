<?php
require __DIR__ . '\\vendor\\autoload.php';
require __DIR__ . '\\Autoloader.php';

use Bobyblog\Router;

session_start();

Router::handleRequest();