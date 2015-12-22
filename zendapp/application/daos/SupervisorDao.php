<?php

class SupervisorDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'supervisors';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('employee_id');
	}

	/**
	 * Used to retrieve a set of supervisors for an employee.
	 *
	 * @param id The id of the employee for which supervisors are to be
	 * retrieved.
	 *
	 * @return Returns the requested supervisors.
	 */
	public function getForEmployee($id)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('employee_id = ?', $id);

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj)
				$this->postProcess($obj);

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve a set of supervisors for a supervisor.
	 *
	 * @param id The id of the supervisor for which employees are to be
	 * retrieved.
	 *
	 * @return Returns the requested supervisors.
	 */
	public function getForSupervisor($id)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('supervisor_id = ?', $id);

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj)
				$this->postProcess($obj);

		// Return the objects.
		return $objs;
	}

	/**
	 * Add the provided array of data as a row in the database.
	 *
	 * @param arr An array of the values to insert into the database.
	 */
	public function add($arr)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Insert the object into the database.
		$db->insert($this->_name, $arr);
	}

	/**
	 * Remove the specified supervisors from the database.
	 *
	 * @param employeeId The employee id of the supervisors to delete.
	 *
	 * @param ids The ids of the supervisors to delete.
	 *
	 * @return Returns the number of rows removed.
	 */
	public function removeAll($employeeId, $ids)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the deleted rows.
		$count = 0;

		// Make sure some ids were specified.
		if (is_numeric($employeeId) && isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id))
					// Delete the object with the specified ids.
					$count += $this->delete(
						"employee_id = $employeeId AND supervisor_id = $id");

		// Return the count.
		return $count;
	}
}

