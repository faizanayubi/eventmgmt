<?php

use Framework\RequestMethods as RequestMethods;
use Framework\ArrayMethods as ArrayMethods;
use Shared\Markup as Markup;

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
    		$event = new Event(array(
    			"title" => RequestMethods::post("title"),
    			"type" => RequestMethods::post("type"),
    			"category" => RequestMethods::post("category"),
    			"description" => RequestMethods::post("description", ""),
    			"start" => RequestMethods::post("start"),
    			"end" => RequestMethods::post("end"),
    			"visibility" => RequestMethods::post("visibility", "public"),
    			"user_id" => $this->user->id,
    			"listingImage" => $this->_upload("listingImage"),
    			"headerImage" => $this->_upload("headerImage"),

    		));

    		$event->save();
            $view->set("success", true);
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

        $events = Event::all(array("live = ?" => true));
        $view->set("events", $events);
    }

    /**
     * The method checks whether a file has been uploaded. If it has, the method attempts to move the file to a permanent location.
     * @param string $name
     * @param string $type files or images
     *
     * @return string|boolean Returns the file name on moving the file successfully else return false
     */
    protected function _upload($name, $type = "images") {
    	if (isset($_FILES[$name])) {
            $file = $_FILES[$name];
            $path = APP_PATH . "/public/assets/uploads/{$type}/";
            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $filename = Markup::generateSalt() . ".{$extension}";
            if (move_uploaded_file($file["tmp_name"], $path . $filename)) {
                return $filename;
            } else {
                return FALSE;
            }
        }
    }

}
