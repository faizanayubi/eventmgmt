<?php

/**
 * The Events Controller
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\ArrayMethods as ArrayMethods;
use Shared\Markup as Markup;

class E extends Organizer {

    public function index() {
        $this->seo(array(
            "title" => "Submit Event",
            "keywords" => "dashboard, events, create event",
            "description" => "Contains all realtime stats",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
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
    		$this->save();
            $view->set("success", 'Event Created <strong>Successfully!</strong>. Go to <a href="/e/manage">Manage Events</a>');
    	}
    }

    /**
     * @before _secure, changeLayout
     */
    public function edit($id) {
    	$event = \Event::first(array("id = ?" => $id));
        if (!$event) {
            self::redirect("/e/manage");
        }

        $this->seo(array(
            "title" => "Dashboard | Edit an Event",
            "keywords" => "dashboard, events, edit event",
            "description" => "Contains all realtime stats",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

    	if (RequestMethods::post("action") == "updateEvent") {
    		$event = $this->save($event);
    		$view->set("message", "Event has been updated");
    	}

    	$view->set("e", $event);
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
        $location = Location::first(array("property = ?" => "event", "property_id = ?" => $event->id));
        $organizer = User::first(array("id = ?" => $event->user_id));
        $similar = Event::all(array("category = ?" => $event->category));

        if (!$event) {
            self::redirect("/");
        }
        $view->set("event", $event);
        $view->set("location", $location);
        $view->set("organizer", $organizer);
        $view->set("similar", $similar);
    }

    /**
     * All Events List to public
     */
    public function all() {
        $this->seo(array(
            "title" => "All Events List",
            "keywords" => "events, new events, event management, create event, event tickets, book event tickets",
            "description" => "Display all the latest events. Filter the events by categories, location and much more. Register yourself to turn your passion into your business",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        $title = RequestMethods::get("title", "");
        $category = RequestMethods::get("category", "");
        $type = RequestMethods::get("type", "");
        $limit = RequestMethods::get("limit", 10);
        $page = RequestMethods::get("page", 1);
        $where = array(
            "title LIKE ?" => "%{$title}%",
            "category LIKE ?" => "%{$category}%",
            "type LIKE ?" => "%{$type}%",
            "live = ?" => true
        );
        $events = Event::all($where, array("*"), "created", "desc", $limit, $page);
        $count = Event::count($where);

        $view->set("events", $events);
        $view->set("limit", $limit);
        $view->set("page", $page);
        $view->set("count", $count);
        $view->set("title", $title);
        $view->set("type", $type);
        $view->set("category", $category);
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

    protected function save($event = null) {
        if (!$event) {
            $event = new \Event(array(
                "listingImage" => $this->_upload("listingImage"),
                "headerImage" => $this->_upload("headerImage")
            ));
        }

        $event->title = RequestMethods::post("title");
        $event->type = RequestMethods::post("type");
        $event->category = RequestMethods::post("category");
        $event->description = RequestMethods::post("description", "");
        $event->start = RequestMethods::post("start");
        $event->end = RequestMethods::post("end");
        $event->visibility = RequestMethods::post("visibility", "public");
        $event->user_id = $this->user->id;
        
        $event->save();
        return $event;
    }

}
