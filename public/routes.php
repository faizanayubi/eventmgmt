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
    ),
    array(
        "pattern" => "logout",
        "controller" => "organizer",
        "action" => "logout"
    ),
    array(
        "pattern" => "event-list",
        "controller" => "e",
        "action" => "index"
    ),
    array(
        "pattern" => "services",
        "controller" => "home",
        "action" => "services"
    ),
    array(
        "pattern" => "about-us",
        "controller" => "home",
        "action" => "about_us"
    )

);

// add defined routes
foreach ($routes as $route) {
    $router->addRoute(new Framework\Router\Route\Simple($route));
}

// unset globals
unset($routes);
