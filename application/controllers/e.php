<?php

use Framework\RequestMethods as RequestMethods;
use Framework\ArrayMethods as ArrayMethods;

/**
 * The Events Controller
 *
 * @author Hemant Mann
 */
class E extends Organizer {

    public function index() {
        
    }

    /**
     * @before _secure, changeLayout
     */
    public function manage() {
    	$this->seo(array(
    		"title" => "Dashboard | Manage Events",
    		"keywords" => "dashboard, events, create event",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $events = Event::all(array("user_id = ?" => $this->user->id));
        $view->set("events", $events);
    }

    /**
     * @before _secure, changeLayout
     */
    public function create() {
    	$this->seo(array(
    		"title" => "Dashboard | Create Event",
    		"keywords" => "dashboard, events, create event",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();
    	
    	if (RequestMethods::post('action') == 'createEvent') {
    		// code to upload file
    		$listingImage = $this->_upload();
    		$headerImage = $this->_upload();

    		$event = new Event(array(
    			"title" => RequestMethods::post("title"),
    			"type" => RequestMethods::post("type"),
    			"category" => RequestMethods::post("category"),
    			"description" => RequestMethods::post("description", ""),
    			"start" => RequestMethods::post("start"),
    			"end" => RequestMethods::post("end"),
    			"visibility" => "public",
    			"user_id" => $this->user->id,
    			"listingImage" => $listingImage,
    			"headerImage" => $headerImage
    		));

    		// $event->save();
    	}
    }

    /**
     * @before _secure, changeLayout
     */
    public function edit($id) {
    	$this->seo(array(
    		"title" => "Dashboard | Edit an Event",
    		"keywords" => "dashboard, events, edit event",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();
    	$event = Event::first(array("id = ?" => $id));

        if (!$event) {
            self::redirect("/e/manage");
        }

    	if (RequestMethods::post("action") == "editEvent") {
    		// update event details
    		// $event->save();

    		$view->set("success", "Event has been updated");
    	}

    	$view->set("event", $event);
    }

    /**
     * @before _secure, changeLayout
     */
    public function bookings($evtId) {
    	$this->seo(array(
    		"title" => "Dashboard | View Bookings",
    		"keywords" => "dashboard, events, create event",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $bookings = Booking::all(array("event_id = ?" => $evtId));
        if (empty($bookings)) {
            self::redirect("/e/manage");
        }

        $b = array();
        $total = 0;
        foreach ($bookings as $booking) {
        	$usr = User::first(array("id = ?" => $booking->user_id), array("name", "email"));
        	$b[] = array(
        		"user" => $usr->name,
        		"tickets" => $booking->tickets,
        		"status" => $booking->status
        	);
        	++$total;
        }
        $b = ArrayMethods::toObject($b);

        $view->set("total", $total);
        $view->set("bookings", $b);
    }

    public function details($title, $id) {
    	$this->seo(array(
    		"title" => "Event Details",
    		"keywords" => "dashboard, events, create event",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $event = Event::first(array("id = ?" => $id));

        if (!$event) {
            self::redirect("/");
        }
        $view->set("event", $event);
    }

    public function all() {
        $this->seo(array(
            "title" => "Events List",
            "keywords" => "events, new events, event management, create event, event tickets, book event tickets",
            "description" => "Display all the latest events. Filter the events by categories, location and much more. Register yourself to turn your passion into your business",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        // search all the events and display them
    }

    protected function _upload() {
    	return "";
    }

}
