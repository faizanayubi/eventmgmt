<?php

/**
 * The Discount Model
 *
 * @author Hemant Mann
 */
class Discount extends Shared\Model {
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
     * @type integer
     * @index
     */
    protected $_ticket_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     *
     * @label Discount Name (Optional)
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     *
     * @label Amount of Discount (Numeric)
     */
    protected $_amount;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @index
     *
     * @label flat|bulk|code
     */
    protected $_category;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 15
     * @index
     *
     * @label fixed|percentage
     */
    protected $_type;

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
     * @type integer
     *
     * @label Discount Limit (default: ticket quantity) (Optional)
     */
    protected $_limit;

    /**
     * @column
     * @readwrtie
     * @type integer
     *
     * @label From Quantity (Needed for bulk discount only)
     */
    protected $_from_quantity;

    /**
     * @column
     * @readwrtie
     * @type integer
     *
     * @label To Quantity (Needed for bulk discount only)
     */
    protected $_to_quantity;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @index
     *
     * @label Needed for code discount only
     */
    protected $_code;
}