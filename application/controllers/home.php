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

        $events = Event::all(array("live = ?" => true, "visibility = ?" => "public"), array("*"), "created", "desc", 50, 1);

        //$slider = Option::all(array("key = ?" => "slider"), array("*"), "created", "desc", 10, 1);
        $gallery = Gallery::all(array(), array("*"), "created", "desc", 10, 1);

        //$view->set("slider", $slider);
        $view->set("gallery", $gallery);
        $view->set("events", $events);
    }

    public function services() {
        $this->seo(array(
            "title" => "Event Management | Services",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

    public function about_us() {
        $this->seo(array(
            "title" => "Event Management | About Us",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

    public function contact() {
    	$this->seo(array(
            "title" => "Event Management | Contact Us",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

}
