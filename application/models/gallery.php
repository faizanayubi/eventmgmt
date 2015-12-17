<?php

/**
 * The Gallery Model
 *
 * @author Hemant Mann
 */
class Gallery extends Shared\Model {
	/**
     * @column
     * @readwrite
     * @type text
     * @length 255
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
    protected $_photo;
}