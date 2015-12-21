<?php

/**
 * Description of instamojo
 *
 * @author Faizan Ayubi
 */
class Instamojo extends Shared\Model {
    
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
     * @type text
     * @length 255
     */
    protected $_payment_request_id;

    /**
     * @column
     * @readwrite
     * @type decimal
     * @length 10
     */
    protected $_amount;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     */
    protected $_purpose;

    /**
     * @column
     * @readwrite
     * @type integer
     */
    protected $_purpose_id;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 64
     */
    protected $_status;

    /**
     * @column
     * @readwrite
     * @type text
     */
    protected $_longurl;
    
}
