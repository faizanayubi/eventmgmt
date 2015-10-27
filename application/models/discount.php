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
     * @index
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_category;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     */
    protected $_type;

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
     * @type text
     * @length 100
     * @index
     */
    protected $_limit;

    /**
     * @column
     * @readwrite
     * @type text
     * @index
     */
    protected $_code;
}