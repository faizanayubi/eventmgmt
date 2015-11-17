<?php

/**
 * The event place Controller Class
 *
 * @author Faizan Ayubi
 */
use Framework\RequestMethods as RequestMethods;

class EventPlace extends E {

    public function index() {
        $this->seo(array(
            "title" => "Places Booking available through us",
            "keywords" => "post events, share events, create events",
            "description" => "Register yourself with MyEventGroup and join the exciting world of Events",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        $locations = Location::all(array("live = ?" => true, "visibility = ?" => "public"), array("*"), "created", "desc", 50, 1);
        $view->set("events", $events);
    }

    /**
     * @before _secure, changeLayout
     */
    public function create() {

    }

}
