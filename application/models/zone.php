<?php

/**
 * The Zone Model
 *
 * @author Hemant Mann
 */
class Zone extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_area;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_city;    

    /**
     * @column
     * @readwrite
     * @type text
     * @length 10
     * @index
     * 
     * @validate required, alpha, min(6), max(10)
     * @label pincode
     */
    protected $_pincode;
}
