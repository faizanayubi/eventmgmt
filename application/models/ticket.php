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
     * @index
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     *
     * @label 0 => Free
     */
    protected $_price;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     *
     * @label INR/USD
     */
    protected $_currency;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_description;

    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_start;

    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_end;

    /**
     * @column
     * @readwrite
     * @type boolean
     */
    protected $_allowCancellation;

    /**
     * @column
     * @readwrite
     * @type text
     *
     * @label 1|2|3
     * [1: Absorb the fees into the ticket price, 2: Absorb gateway 
     * fee and pass on Explara fee to the buyer, 3: Pass on the fees to the buyer]
     */
    protected $_serviceFee;

}
