<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi
 */
use Shared\Controller as Controller;

class Home extends Controller {

    public function index() {
        $this->seo(array(
            "title" => "Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

}