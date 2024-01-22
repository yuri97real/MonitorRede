<?php

use Core\Router;

$router = new Router;

$router->get("/", "HostController::index");
$router->get("/hosts", "HostController::getHosts");

$router->get("/ping/{host}", "PingController::index");

$router->dispatch();