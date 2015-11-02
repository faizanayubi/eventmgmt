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
	 * @type decimal
	 *
	 * @label Amount to be collected from Customer (Mandatory)
	 */
	protected $_amount;

	/**
	 * @column
	 * @readwrite
	 * @type text
	 * @length 3
	 * 
	 * @label Number of Tickets purchased
	 */
	protected $_tickets;

	/**
	 * @column
	 * @readwrite
	 * @type integer
	 * @index
	 */
	protected $_event_id;

	/**
	 * @column
	 * @readwrite
	 * @type text
	 * @length 100
	 */
	protected $_status;

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
	 * @length 10
	 *
	 * @label Currency [INR|USD|EUR]
	 */
	protected $_currency;

	/**
	 * @column
	 * @readwrite
	 * @type text
	 * @length 50
	 *
	 * @label Payment Gateway [MASTER|MAESTRO, without MASTER|MAESTRO]
	 */
	protected $_pg;

}