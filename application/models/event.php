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
     * @length 50
     * @index
     *
     * @label ticekting|conference|charity|rsvp
     */
    protected $_type;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * @index
     *
     * @label business|technology|entertainment|sports|workshop|fundraising etc
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
     * @type datetime
     * @index
     */
    protected $_start;

    /**
     * @column
     * @readwrite
     * @type datetime
     * @index
     */
    protected $_end;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     *
     * @label Listing Image Name
     */
    protected $_listingImage;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     *
     * @label Header Image Name
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
     * @type integer
     * @type index
     */
    protected $_user_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @index
     *
     * @label public|private
     */
    protected $_visibility;

}
