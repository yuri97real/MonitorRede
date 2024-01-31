<?php

use Core\Router;

$router = new Router;

$router->get("/", "HostController::index");

$router->get("/hosts", "HostController::getHosts");
$router->post("/hosts", "HostController::createHost");
$router->put("/hosts/{host_id}", "HostController::updateHost");
$router->delete("/hosts/{host_id}", "HostController::deleteHost");

$router->get("/hosts/categories", "HostCategoryController::getCategories");
$router->post("/hosts/categories", "HostCategoryController::createCategory");
$router->put("/hosts/categories/{category_id}", "HostCategoryController::updateCategory");
$router->delete("/hosts/categories/{category_id}", "HostCategoryController::deleteCategory");

$router->get("/ping/{host}", "PingController::index");

$router->dispatch();