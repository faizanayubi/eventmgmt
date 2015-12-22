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

    /**
     * All Events List to public
     */
    public function index() {
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

    public function inquiry() {
        $this->seo(array(
            "title" => "Submit Event",
            "keywords" => "dashboard, events, create event",
            "description" => "Contains all realtime stats",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "submitEvent") {
            if ($this->user) {
                $user = $this->user;
            } else {
                $user = new User(array(
                    "name" => RequestMethods::post("name"),
                    "email" => RequestMethods::post("email"),
                    "phone" => RequestMethods::post("phone"),
                    "password" => sha1(rand(999999,99999999)),
                    "admin" => 0
                ));
                $user->save();
            }
            $event = new Event(array(
                "title" => RequestMethods::post("title"),
                "type" => RequestMethods::post("type", "rsvp"),
                "category" => "inquiry",
                "description" => RequestMethods::post("description"),
                "start" => "",
                "end" => "",
                "listingImage" => "",
                "headerImage" => "",
                "location_id" => "",
                "city" => RequestMethods::post("city"),
                "user_id" => $user->id,
                "visibility" => RequestMethods::post("visibility", "private")
            ));
            $event->save();

            $view->set("message", "Saved Successfully, Our representative will call you within 24 Hours, Check your Email");
        }
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
     * @before _secure, adminLayout
     */
    public function all() {
        $this->seo(array("title" => "Manage Events", "view" => $this->getLayoutView()));
        $view = $this->getActionView();

        $page = RequestMethods::get("page", 1);
        $limit = RequestMethods::get("limit", 10);
        
        $events = Event::all(array(), array("title", "start", "listingImage", "city", "user_id"), "created", "desc", $limit, $page);
        $count = Event::count();

        $view->set("events", $events);
        $view->set("page", $page);
        $view->set("count", $count);
        $view->set("limit", $limit);
    }

    /**
     * @before _secure, changeLayout
     */
    public function gallery($event_id) {
        $this->seo(array(
            "title" => "Dashboard | Gallery",
            "keywords" => "dashboard, events, create event",
            "description" => "Contains all realtime stats",
            "view" => $this->getLayoutView()
        ));
        $view = $this->getActionView();
        $event = \Event::first(array("id = ?" => $event_id));
        if (!$event || $event->user_id != $this->user->id) {
            self::redirect("/organizer");
        }

        if (RequestMethods::post("action") == "uploadPhotos") {
            $file = $this->_upload("image");
            if ($file) {
                $gallery = new Gallery(array(
                    "event_id" => $event_id,
                    "photo" => $file
                ));
                $gallery->save();
            }
            $view->set('success', "Images uploaded successfully!");
        }

        $gallery = Gallery::all(array("event_id = ?" => $event_id));
        $view->set("gallery", $gallery);
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

    public function details($title, $id = NULL) {
        if ($id == NULL) {
            self::redirect("/");
        }
        $event = Event::first(array("id = ?" => $id));
        $ticket = Ticket::first(array("event_id = ?" => $event->id));
        $location = Location::first(array("user_id = ?" => $event->user_id));
    	$this->seo(array(
    		"title" => $event->title,
    		"keywords" => "dashboard, events, create event",
    		"description" => strip_tags(substr($event->description, 0, 150)),
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $organizer = User::first(array("id = ?" => $event->user_id));
        $similar = Event::all(array("category = ?" => $event->category));

        if (RequestMethods::post("action") == "booking") {
            $booking = Booking::first(array("user_id = ?" => $this->user->id, "event_id = ?" => $event->id));
            if ($booking) {
                $link = $booking->paylink;
            } else {
                if ($this->user) {
                    $user = $this->user;
                } else{
                    $user = new User(array(
                        "name" => RequestMethods::post("name"),
                        "email" => RequestMethods::post("email"),
                        "phone" => RequestMethods::post("phone"),
                        "password" => sha1(100000, 999999),
                        "admin" => false
                    ));
                    $user->save();
                    $this->notify(array(
                        "template" => "userRegister",
                        "subject" => "Welcome to MyEventGroup.com",
                        "user" => $user
                    ));
                }

                $link = $ticket->book($user);
            }
            self::redirect($link);
        }

        $view->set("event", $event);
        $view->set("ticket", $ticket);
        $view->set("location", $location);
        $view->set("organizer", $organizer);
        $view->set("similar", $similar);
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
