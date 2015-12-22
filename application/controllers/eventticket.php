<?php

/**
 * The Event Tickets Controller
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class EventTicket extends E {

    /**
     * @before _secure, changeLayout
     */
    public function create() {
    	$evntId = RequestMethods::get("event");
        $event = \Event::first(array("id = ?" => $evntId), array("title", "id", "end"));
        if (!$event) {
            self::redirect("/e/manage");
        }
        $this->seo(array("title" => "Create Event Ticket","view" => $this->getLayoutView()));
        $view = $this->getActionView();
        $view->set("event", $event);

        if (RequestMethods::post("action") == "createTicket") {
        	if (RequestMethods::post("eventId") != $evntId) {
                $view->set("message", "Invalid Request");
                return;
            }

            $this->save($event);
            $view->set("message", 'Ticket Created <strong>Successfully!</strong>. Go to <a href="/e/manage">Manage Events</a>');
        }
    }

    /**
     * @before _secure, changeLayout
     */
    public function update() {
        $evtId = RequestMethods::get("eventId");
        $ticket = \Ticket::first(array("event_id = ?" => $evtId));
        $event = \Event::first(array("id = ?" => $evtId), array("id", "end", "title"));
        if (!$ticket || !$event) {
            self::redirect("/e/manage");
        }
    	$this->seo(array("title" => "Update Event Ticket","view" => $this->getLayoutView()));
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "updateTicket") {
        	$this->save($event, $ticket);
            $view->set("message", 'Ticket Updated <strong>Successfully!</strong>. Go to <a href="/e/manage">Manage Events</a>');
        }
        $view->set("ticket", $ticket);
        $view->set("event", $event);
    }

    public function success() {
        $this->noview();
        $payment_request_id = RequestMethods::get("payment_request_id");

        if ($payment_request_id) {
            $instamojo = Instamojo::first(array("payment_request_id = ?" => $payment_request_id));

            if ($instamojo) {
                $imojo = $configuration->parse("configuration/payment");
                $curl = new Curl();
                $curl->setHeader('X-Api-Key', $imojo->payment->instamojo->key);
                $curl->setHeader('X-Auth-Token', $imojo->payment->instamojo->auth);
                $curl->get('https://www.instamojo.com/api/1.1/payment-requests/'.$payment_request_id.'/');
                $payment = $curl->response;

                $instamojo->status = $payment->payment_request->status;
                $instamojo->save();

                $booking = Booking::first(array("user_id = ?" => $instamojo->user_id, "event_id = ?" => $instamojo->purpose_id));
                $event = \Event::first(array("id = ?" => $booking->event_id), array("id", "start", "end", "title"));
                $location = Location::first(array("id = ?" => $event->location_id), array("address", "city"));
                $ticket = \Ticket::first(array("event_id = ?" => $event->id), array("price"));
                $user = User::first(array("id = ?" => $instamojo->user_id));
                $this->notify(array(
                    "template" => "confirmTicket",
                    "subject" => "Your Event Ticket",
                    "booking" => $booking,
                    "event" => $even,
                    "location" => $location,
                    "ticket" => $ticket,
                    "user" => $user,
                ));

                self::redirect("/booking/". $booking->id);
            }

        }
    }

    public function booking($id) {
        $this->defaultLayout = "layouts/other/ticket";
        $this->setLayout();
        $this->seo(array("title" => "Ticket","view" => $this->getLayoutView()));

        $view = $this->getActionView();
        $booking = Booking::first(array("id = ?" => $id));
        $event = \Event::first(array("id = ?" => $booking->event_id), array("id", "start", "end", "title"));
        $location = Location::first(array("id = ?" => $event->location_id), array("address", "city"));
        $ticket = \Ticket::first(array("event_id = ?" => $event->id), array("price"));
        $user = User::first(array("id = ?" => $booking->user_id), array("name", "email"));

        $view->set("ticket", $ticket);
        $view->set("event", $event);
        $view->set("location", $location);
        $view->set("user", $user);
        $view->set("booking", $booking);
    }

    protected function save($event, $ticket = null) {
        if (!$ticket) {
            $ticket = new \Ticket();
        }

        $ticket->name = RequestMethods::post("name");
        $ticket->price = RequestMethods::post("price");
        $ticket->currency = RequestMethods::post("currency");
        $ticket->description = RequestMethods::post("description");
        $ticket->start = RequestMethods::post("start", date('Y-m-d H:i:s'));
        $ticket->end = RequestMethods::post("end", $event->end);
        $ticket->allowCancellation = RequestMethods::post("cancel");
        $ticket->serviceFee = RequestMethods::post("serviceFee");
        $ticket->quantity = RequestMethods::post("quantity");
        $ticket->min_quantity = RequestMethods::post("minQuantity", "1");
        $ticket->max_quantity = RequestMethods::post("maxQuantity", "10");
        $ticket->event_id = $event->id;

        $ticket->save();
        return $ticket;
    }

}
