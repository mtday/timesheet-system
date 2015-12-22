<?php

class BillDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'bills';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('employee_id')
		       ->order('contract_id');
	}

	/**
	 * Used to retrieve all the bills for an employee's timesheet.
	 *
	 * @param id The timesheet id for which bills are to be retrieved.
	 *
	 * @param empId The employee id for which bills are to be retrieved.
	 *
	 * @return Returns all the bills for the specified timesheet.
	 */
	public function getForTimesheet($id, $empId)
	{
		// Make sure the provided id is valid.
		if (!isset($id) || !is_numeric($id) ||
				!isset($empId) || !is_numeric($empId))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the DAOs we will be joining with.
		$timesheetDao = new TimesheetDao();
		$payPeriodDao = new PayPeriodDao();

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from(array('b' => $this->_name))
			   ->join(array('p' => $payPeriodDao->_name),
					   'b.day >= p.start && b.day <= p.end')
			   ->join(array('t' => $timesheetDao->_name),
					   "t.pp_start = p.start AND " .
						   "t.id = $id AND t.employee_id = $empId",
					   array('completed', 'approved', 'verified'))
			   ->where('b.employee_id = ?', $empId);

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj)
				// Do the post processing.
				$this->postProcess($obj);

		// Return the objects.
		return $objs;
	}
}

