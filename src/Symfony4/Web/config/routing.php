<?php

use ZnBundle\User\Symfony4\Web\Controllers\AuthController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add('user/auth', '/auth')
        ->controller([AuthController::class, 'auth'])
        ->methods(['GET', 'POST']);
    $routes
        ->add('user/logout', '/logout')
        ->controller([AuthController::class, 'logout'])
        ->methods(['POST']);
};
