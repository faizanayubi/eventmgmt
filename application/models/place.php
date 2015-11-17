<?php

/**
 * The Place Model
 *
 * @author Faizan Ayubi
 */
class Place extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type integer
     * @index
     */
    protected $_user_id;

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
     * @type text
     */
    protected $_details;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_image;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_charge;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_peroid;
}
