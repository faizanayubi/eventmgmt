<?php

/**
 * Description of organization
 *
 * @author Faizan Ayubi
 */
class Organization extends Shared\Model {

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
     * @type text
     */
    protected $_details;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_website;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_email;
    
    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     */
    protected $_image;
}
