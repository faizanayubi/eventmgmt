<?php

/**
 * The Ticket Model
 *
 * @author Hemant Mann
 */
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
     * @type text
     * @length 10
     *
     * @label INR|USD
     */
    protected $_currency;

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label Description of Ticket (Optional)
     */
    protected $_description;

    /**
     * @column
     * @readwrite
     * @type datetime
     *
     * @label Default today's date (Optional)
     */
    protected $_start;

    /**
     * @column
     * @readwrite
     * @type datetime
     *
     * @label Default event end date (Optional)
     */
    protected $_end;

    /**
     * @column
     * @readwrite
     * @type boolean
     *
     * @label true|false
     */
    protected $_allowCancellation;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 2
     *
     * @label 1|2|3
     * [1: Absorb the fees into the ticket price, 2: Absorb gateway 
     * fee and pass on our fee to the buyer, 3: Pass on the fees to the buyer]
     */
    protected $_serviceFee;

}
