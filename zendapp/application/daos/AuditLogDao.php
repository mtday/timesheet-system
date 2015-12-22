<?php

class AuditLogDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'audit_logs';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('timesheet_id')
		       ->order('timestamp');
	}

	/**
	 * Used to retrieve all the audit logs for a timesheet.
	 *
	 * @param id The timesheet id for which audit logs are to be retrieved.
	 *
	 * @return Returns all the audit logs for the specified timesheet.
	 */
	public function getForTimesheet($id)
	{
		// Make sure the provided id is valid.
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
			   ->where('timesheet_id = ?', $id);

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
}

