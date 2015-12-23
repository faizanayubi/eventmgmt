<?php

/**
 * The Default Example Controller Class
 *
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;

class Home extends Auth {

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
            "title" => "Services Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

    public function features() {
        $this->seo(array(
            "title" => "Features Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

    public function about_us() {
        $this->seo(array(
            "title" => "About Us Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
    }

    public function contact() {
    	$this->seo(array(
            "title" => "Contact Us Event Management",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        if (RequestMethods::post("submit") == "Send") {
            $this->notify(array(
                "template" => "message",
                "subject" => "You have received a message",
                "cmessage" => RequestMethods::post("message"),
                "sender" => RequestMethods::post("name"). ", " . RequestMethods::post("email"),
                "user" => User::first(array("id = ?" => 1))
            ));

            $view->set("message", "Your message has been received, we will contact you within 24 hours.");
        }
    }

}
