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
     *
     * @label business/technology
     */
    protected $_category;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     *
     * @label ticekting/conference
     */
    protected $_type;

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
     * @type text
     */
    protected $_listingImage;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_headerImage;

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

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     *
     * @label public/private
     */
    protected $_visibility;

}