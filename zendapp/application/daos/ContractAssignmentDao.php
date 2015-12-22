<?php

class ContractAssignmentDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'contract_assignments';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('employee_id')
			   ->order('contract_id')
			   ->order('labor_cat')
			   ->order('item_name');
	}

	/**
	 * Used to retrieve a contract assignment.
	 *
	 * @param id The id of the contract assignment to be retrieved.
	 *
	 * @return Returns the requested contract assignment.
	 */
	public function getAssignment($id)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return null;

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)->where('id = ?', $id);

		// Retrieve the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->contract_id) || !isset($obj->employee_id))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object.
		return $obj;
	}

	/**
	 * Used to retrieve a set of contract assignments for an employee.
	 *
	 * @param employeeId The id of the employee for which contract assignments
	 * are to be retrieved.
	 *
	 * @return Returns the requested contract assignments.
	 */
	public function getForEmployee($employeeId)
	{
		// Make sure the employee id is valid.
		if (!isset($employeeId) || !is_numeric($employeeId))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('employee_id = ?', $employeeId);

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
	 * Used to retrieve a set of contract assignments for a contract.
	 *
	 * @param contractId The id of the contract for which contract assignments
	 * are to be retrieved.
	 *
	 * @return Returns the requested contract assignments.
	 */
	public function getForContract($contractId)
	{
		// Make sure the contract id is valid.
		if (!isset($contractId) || !is_numeric($contractId))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('contract_id = ?', $contractId);

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
	 * Remove the objects with the specified ids from the database.
	 *
	 * @param ids The unique assignment ids of the objects to delete.
	 *
	 * @return Returns the number of rows removed.
	 */
	public function removeAssignments($ids)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the deleted rows.
		$count = 0;

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id))
					// Delete the assignment with the specified ids.
					$count += $this->delete("id = $id");

		// Return the count.
		return $count;
	}

	/**
	 * Update the assignment represented by the provided id with the provided
	 * array of values.
	 *
	 * @param id The id of the assignment to modify.
	 *
	 * @param arr The array of new values.
	 */
	public function saveAssignment($id, $arr)
	{
		// Make sure the id is valid.
		if (isset($id) && is_numeric($id)) {
			// Get the database adapter.
			$db = $this->getAdapter();

			// Update the object in the database.
			$db->update($this->_name, $arr, "id = $id");
		}
	}
}

