<?php

class PayPeriodDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'pay_periods';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('start desc');
	}

	/**
	 * Used to retrieve the current pay period.
	 *
	 * @return Returns the current pay period.
	 */
	public function getCurrent()
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('`start` <= ?', date('Y-m-d'))
			   ->where('`end` >= ?',   date('Y-m-d'));

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->start))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Used to retrieve the pay period containing the specified date.
	 *
	 * @return Returns the requested pay period.
	 */
	public function getContaining($date)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('`start` <= ?', $date)
			   ->where('`end` >= ?',   $date);

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->start))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Used to retrieve the latest pay period.
	 *
	 * @return Returns the latest pay period.
	 */
	public function getLatest()
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->order('start desc')
			   ->limit(1);

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->start))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Retrieve a pay period object.
	 *
	 * @param start The start date of the pay period to retrieve.
	 *
	 * @return Returns the requested pay period from the database.
	 */
	public function get($start)
	{
		// Make sure the start is valid.
		if (!isset($start))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('`start` = ?', $start);

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->start))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Populate the pay periods up through the current pay period.
	 *
	 * @return Returns the current timesheet after it is created.
	 */
	public function addThroughCurrent()
	{
		// Get the latest pay period.
		$latest = $this->getLatest();

		// Get the milliseconds for right now.
		$now = strtotime(date('Y-m-d'));

		// Make sure the latest pay period was found.
		while (isset($latest) && !PayPeriodHelper::isCurrent($latest)) {
			// Get the next pay period.
			$latest = PayPeriodHelper::getNext($latest);

			// Add this pay period to the database.
			$this->add(array(
				'start'  => $latest->start,
				'end'    => $latest->end,
				'type'   => $latest->type
			));
		}

		// Return the latest pay period, which is the current pay period.
		return $latest;
	}
}

