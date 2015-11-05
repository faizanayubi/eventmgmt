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

        $events = Event::all(array("live = ?" => true), array("*"), "created", "desc", 50, 1);
        $view->set("events", $events);
    }

    public function contact() {
    	$this->seo(array(
            "title" => "Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

}
