<?php

/**
 * The Event Tickets Controller
 *
 * @author Hemant Mann
 */
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;
use \Curl\Curl;

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
        $this->seo(array(
            "title" => "Dashboard | Create Event Ticket",
            "keywords" => "dashboard",
            "description" => "Contains all realtime stats",
            "view" => $this->getLayoutView()
        ));
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
    	$this->seo(array(
    		"title" => "Dashboard | Update Event Ticket",
    		"keywords" => "dashboard",
    		"description" => "Contains all realtime stats",
    		"view" => $this->getLayoutView()
    	));
        $view = $this->getActionView();

        if (RequestMethods::post("action") == "updateTicket") {
        	$this->save($event, $ticket);
            $view->set("message", 'Ticket Updated <strong>Successfully!</strong>. Go to <a href="/e/manage">Manage Events</a>');
        }
        $view->set("ticket", $ticket);
        $view->set("event", $event);
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

    protected function payLink($charge, $purpose, $purpose_id) {
        $configuration = Registry::get("configuration");
        $imojo = $configuration->parse("configuration/payment");
        $curl = new Curl();
        $curl->setHeader('X-Api-Key', $imojo->payment->instamojo->key);
        $curl->setHeader('X-Auth-Token', $imojo->payment->instamojo->auth);
        $curl->post('https://www.instamojo.com/api/1.1/payment-requests/', array(
            "purpose" => "Appointment",
            "amount" => $charge,
            "buyer_name" => $this->user->name,
            "email" => $this->user->email,
            "phone" => $this->user->phone,
            "redirect_url" => "http://healthlitmus.com/appointments/success",
            "webhook_url" => "http://healthlitmus.com/appointments/success",
            "allow_repeated_payments" => false
        ));

        $payment = $curl->response;
        if ($payment->success == "true") {
            $instamojo = new Instamojo(array(
                "user_id" => $this->user->id,
                "payment_request_id" => $payment->payment_request->id,
                "amount" => $payment->payment_request->amount,
                "purpose" => $purpose,
                "purpose_id" => $purpose_id,
                "status" => $payment->payment_request->status,
                "longurl" => $payment->payment_request->longurl,
                "live" => 1
            ));
            $instamojo->save();

            return $instamojo->longurl;
        }
    }

}
