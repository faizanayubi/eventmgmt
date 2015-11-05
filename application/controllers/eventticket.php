<?php

use Framework\RequestMethods as RequestMethods;

/**
 * The Event Tickets Controller
 *
 * @author Hemant Mann
 */
class EventTicket extends E {

    public function index() {
        
    }

    /**
     * @before _secure, changeLayout
     */
    public function create($evntId) {
    	$this->seo(array(
    		"title" => "Dashboard | Create Event Ticket",
    		"keywords" => "dashboard",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();
        $event = Event::first(array("id = ?" => $evntId), array("title", "id", "end"));
        $view->set("event", $event);

        if (RequestMethods::post("action") == "createTicket") {
        	if (RequestMethods::post("eventId") != $evntId) {
                $view->set("message", "Invalid Request");
                return;
            }
            $ticket = new Ticket(array(
                "name" => RequestMethods::post("name"),
                "price" => RequestMethods::post("price"),
                "currency" => RequestMethods::post("currency"),
                "description" => RequestMethods::post("description"),
                "start" => RequestMethods::post("start", date('Y-m-d H:i:s')),
                "end" => RequestMethods::post("end", $event->end),
                "allowCancellation" => RequestMethods::post("cancel"),
                "serviceFee" => RequestMethods::post("serviceFee"),
                "quantity" => RequestMethods::post("quantity"),
                "min_quantity" => RequestMethods::post("minQuantity", "1"),
                "max_quantity" => RequestMethods::post("maxQuantity", "10"),
                "event_id" => $evntId
            ));
            $ticket->save();
            $view->set("message", 'Ticket Created <strong>Successfully!</strong>. Go to <a href="/e/manage">Manage Events</a>');
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function update($evtId) {
    	$this->seo(array(
    		"title" => "Dashboard | Update Event Ticket",
    		"keywords" => "dashboard",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $ticket = Ticket::first(array("event_id = ?" => $evtId));

        if (RequestMethods::post("action") == "updateTicket") {
        	// update ticket details
        	// $ticket->save();
        }
        $view->set("ticket", $ticket);
    }

    /**
     * @before _secure, changeLayout
     */
    public function view($evtId) {
    	$this->seo(array(
    		"title" => "Dashboard | Manage Event Ticket",
    		"keywords" => "dashboard",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        $ticket = Ticket::first(array("event_id = ?" => $evtId));
        $view->set("ticket", $ticket);
    }

}
