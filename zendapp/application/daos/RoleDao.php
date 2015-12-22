<?php

class RoleDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'roles';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('employee_id')
			   ->order('name');
	}

	/**
	 * Used to retrieve a set of roles for an employee.
	 *
	 * @param id The id of the employee for which roles are to be retrieved.
	 *
	 * @return Returns the requested roles.
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
	 * Remove all the roles for the specified employee id.
	 *
	 * @param id The employee id for which roles will be deleted.
	 *
	 * @return Returns the number of rows removed.
	 */
	public function removeForEmployee($id)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Make sure an id was specified.
		if (isset($id) && is_numeric($id) > 0)
			// Delete the objects with the specified employee id.
			return $this->delete($db->quoteInto('employee_id = ?', $id));

		// Return 0 if we made it here.
		return 0;
	}
}

