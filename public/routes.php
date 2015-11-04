<?php

// define routes

$routes = array(
    array(
        "pattern" => "home",
        "controller" => "home",
        "action" => "index"
    ),
    array(
        "pattern" => "contact-us",
        "controller" => "home",
        "action" => "contact"
    ),
    array(
        "pattern" => "login",
        "controller" => "organizer",
        "action" => "login"
    ),
    array(
        "pattern" => "register",
        "controller" => "organizer",
        "action" => "register"
    )
);

// add defined routes
foreach ($routes as $route) {
    $router->addRoute(new Framework\Router\Route\Simple($route));
}

// unset globals
unset($routes);
