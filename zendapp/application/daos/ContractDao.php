<?php

class ContractDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'contracts';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('admin')
			   ->order('description')
			   ->order('contract_num')
			   ->order('job_code');
	}

	/**
	 * Retrieve all the contract objects in the database.
	 *
	 * @return Returns an array of all the contracts in the database.
	 */
	public function getAll($regularOnly = null)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name);

		// Add the administrative clause if necessary.
		if ($regularOnly)
			$select->where('admin = false');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the identified employees.
			foreach ($objs as $obj)
				// Perform the post-processing.
				$this->postProcess($obj);

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve a set of contracts assigned to an employee.
	 *
	 * @param id The employee id for which assigned contracts are to be
	 * retrieved.
	 *
	 * @param payPeriod The payPeriod for which contract assignments must be
	 * valid.
	 *
	 * @return Returns the requested employee contracts.
	 */
	public function getEmployeeContractsForPayPeriod($id, $payPeriod)
	{
		// Make sure the id is valid.
		if (!isset($id) || !is_numeric($id))
			return array();

		// Make sure the pay period is valid.
		if (!isset($payPeriod) || !isset($payPeriod->start)
				|| !isset($payPeriod->end))
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Get the DAO for the table we will be joining with.
		$assignmentDao = new ContractAssignmentDao();

		// Build the query.
		$select->from(array('c' => $this->_name),
					   array('id AS contract_id', 'description',
						   'contract_num', 'job_code', 'admin', 'active'))
			   ->joinLeft(array('a' => $assignmentDao->_name),
					   'a.contract_id = c.id',
					   array('id AS assignment_id', 'employee_id', 'labor_cat',
						   'item_name', 'start', 'end'))
			   ->where('a.employee_id = ? OR c.admin = true', $id);

		// Add the clause to prevent expired contracts from being retrieved.
		$select->where('start IS NULL OR start <= ?', $payPeriod->end);
		$select->where('end IS NULL OR end >= ?', $payPeriod->start);

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
	 * Used to retrieve a set of contracts assigned to an employee.
	 *
	 * @param id The employee id for which assigned contracts are to be
	 * retrieved.
	 *
	 * @param day The day for which contract assignments must be valid.
	 *
	 * @param regularOnly Whether administrative contracts should be included.
	 *
	 * @return Returns the requested employee contracts.
	 */
	public function getEmployeeContracts($id, $day = null, $regularOnly = false)
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

		// Get the DAO for the table we will be joining with.
		$assignmentDao = new ContractAssignmentDao();

		// Build the query.
		$select->from(array('c' => $this->_name),
					   array('id AS contract_id', 'description',
						   'contract_num', 'job_code', 'admin', 'active'))
			   ->joinLeft(array('a' => $assignmentDao->_name),
					   'a.contract_id = c.id',
					   array('id AS assignment_id', 'employee_id', 'labor_cat',
						   'item_name', 'start', 'end'))
			   ->where('a.employee_id = ? OR c.admin = true', $id);

		// Make sure the pay period is not null before adding the
		// clause to prevent expired contracts from being retrieved.
		if ($day) {
			$select->where('start IS NULL OR start <= ?', $day);
			$select->where('end IS NULL OR end >= ?', $day);
		}

		// Make sure administrative contracts aren't included if that is
		// what was requested.
		if ($regularOnly)
			$select->where('c.admin = false');

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

