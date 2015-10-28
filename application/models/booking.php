<?php

/**
 * The Booking Model
 *
 * @author Hemant Mann
 */
class Booking extends Shared\Model {
	/**
	 * @column
	 * @readwrite
	 * @type text
	 * @index
	 */
	protected $_amount;

	/**
	 * @column
	 * @readwrite
	 * @type integer
	 * @index
	 */
	protected $_order_id;

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
	 *
	 * @label Currency [INR|USD|EUR]
	 */
	protected $_currency;

	/**
	 * @column
	 * @readwrite
	 * @type text
	 *
	 * @label Payment Gateway [MASTER|MAESTRO, without MASTER|MAESTRO]
	 */
	protected $_pg;

}