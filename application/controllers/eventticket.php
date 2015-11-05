<?php

use Framework\RequestMethods as RequestMethods;

/**
 * The Event Tickets Controller
 *
 * @author Hemant Mann
 */
use Shared\Controller as Controller;

class EventTicket extends Controller {

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

        if (RequestMethods::post("action") == "createTicket") {
        	/*
        	$ticket = new Ticket(array(

        	));
        	$ticket->save();
        	*/
        }
    }

    /**
     * @before _secure
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
