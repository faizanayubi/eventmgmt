<?php

/**
 * The Option Model
 *
 * @author Hemant Mann
 */
class Option extends Shared\Model {
	/**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @index
     */
    protected $_key;

    /**
     * @column
     * @readwrite
     * @type text
     * @length 255
     * @index
     */
    protected $_value;
}