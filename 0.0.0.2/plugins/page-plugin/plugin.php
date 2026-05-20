<?php
use Plugins\PagePlugin\Controllers\PagePluginController;

$eventManager->listen('router.register', function ($router) {
    $router->get('/', [PagePluginController::class, 'home']);
    $router->get('/about', [PagePluginController::class, 'about']);
});