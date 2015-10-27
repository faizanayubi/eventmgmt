<?php

/**
 * The Event Model
 *
 * @author Hemant Mann
 */
class Event extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @index
     */
    protected $_title;

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
     */
    protected $_description;

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_location_id;

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_zone_id;

}
