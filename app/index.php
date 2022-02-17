<?php
require_once "../config/Autoloader.php";

use Config\Autoloader;
Autoloader::register();

use Config\Router;

$router = new Router();
$router->run();
