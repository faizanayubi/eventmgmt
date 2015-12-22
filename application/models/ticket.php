<?php

/**
 * The Ticket Model
 *
 * @author Hemant Mann
 */
use Framework\Registry as Registry;
use \Curl\Curl;

class Ticket extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_event_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type decimal
     * @length 11,2
     * @index
     *
     * @label 0.00 => Free
     */
    protected $_price = 0.00;

    /**
     * @column
     * @readwrite
     * @type integer
     *
     * @label Ticket|Registration Quantity
     */
    protected $_quantity;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 1
     *
     * @label Minimum purchase quantity (default: 1)
     */
    protected $_min_quantity = "1";

    /**
     * @column
     * @readwrite
     * @type text
     * @length 3
     *
     * * @label Maximum purchase quantity (default: 10)
     */
    protected $_max_quantity = "10";

    /**
     * @column
     * @readwrite
     * @type boolean
     *
     * @label true|false
     */
    protected $_allowCancellation;

    public function book($user, $tickets=1) {
        $booking = new Booking(array(
            "amount" => $tickets*$this->price,
            "tickets" => $tickets,
            "event_id" => $this->event_id,
            "user_id" => $user->id,
            "paylink" => $this->payLink($user)
        ));
        $booking->save();

        return $booking->paylink;
    }

    protected function payLink($user) {
        $configuration = Registry::get("configuration");
        $imojo = $configuration->parse("configuration/payment");
        $curl = new Curl();
        $curl->setHeader('X-Api-Key', $imojo->payment->instamojo->key);
        $curl->setHeader('X-Auth-Token', $imojo->payment->instamojo->auth);
        $curl->post('https://www.instamojo.com/api/1.1/payment-requests/', array(
            "purpose" => "Event",
            "amount" => $this->price,
            "buyer_name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "redirect_url" => "http://myeventgroup.com/eventticket/success",
            "webhook_url" => "http://myeventgroup.com/eventticket/success",
            "allow_repeated_payments" => false
        ));

        $payment = $curl->response;
        if ($payment->success == "true") {
            $instamojo = new Instamojo(array(
                "user_id" => $this->user->id,
                "payment_request_id" => $payment->payment_request->id,
                "amount" => $payment->payment_request->amount,
                "purpose" => "event",
                "purpose_id" => $this->event_id,
                "status" => $payment->payment_request->status,
                "longurl" => $payment->payment_request->longurl,
                "live" => 1
            ));
            $instamojo->save();

            return $instamojo->longurl;
        }
    }

}
