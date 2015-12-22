<?php

class EmployeeDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'employees';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('last_name')
			   ->order('first_name');
	}

	/**
	 * Perform post-processing on the records retrieved from the database.
	 *
	 * @param obj The database object to perform processing on.
	 */
	public function postProcess($obj)
	{
		// Set the full name field.
		if (isset($obj) && isset($obj->first_name) && isset($obj->last_name)) {
			// Set the full name.
			$obj->full_name = $obj->first_name . ' ' . $obj->last_name;

			// Add the name suffix.
			if (isset($obj->suffix))
				$obj->full_name .= ' ' . $obj->suffix;
		}

		// Make sure the roles are available.
		if (isset($obj) && isset($obj->roles)) {
			// Set the flag tracking whether the employee has extra privileges.
			$obj->has_privileges = count($obj->roles) > 0;

			// This will hold a representation of the employee's privileges.
			$obj->privileges = "";

			// Add all the roles to the employee object.
			foreach ($obj->roles as $role) {
				// Add this role to the employee.
				$name = $role->name;
				$obj->$name = true;

				// Update the privileges based on this role.
				$obj->privileges .= ($obj->privileges ? " " : "") .
					strtoupper(substr($role->name, 0, 1));
			}

			// Make sure all the roles have been set.
			if (!isset($obj->admin))    $obj->admin    = false;
			if (!isset($obj->manager))  $obj->manager  = false;
			if (!isset($obj->payroll))  $obj->payroll  = false;
			if (!isset($obj->security)) $obj->security = false;
			if (!isset($obj->wiki))     $obj->wiki     = false;
		}

		// Make sure the supervised are available.
		if (isset($obj) && isset($obj->supervised))
			// Add the flag for being a supervisor.
			$obj->supervisor = count($obj->supervised) > 0;
	}

	/**
	 * Used to retrieve an employee by id.
	 *
	 * @param id The id for which to find the employee.
	 *
	 * @return Returns the employee with the specified id,
	 *         or null if that employee does not exist.
	 */
	public function get($id)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('id = ?', $id);

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Make sure the employee id value is valid.
		if (is_numeric($obj->id)) {
			// Set the employee's roles.
			$roleDao = new RoleDao();
			$obj->roles = $roleDao->getForEmployee($obj->id);

			// Set the employee's supervisors.
			$obj->supervisors = $this->getSupervisorEmployees($obj->id);

			// Set the employees this employee is supervising.
			$obj->supervised = $this->getSupervisedEmployees($obj->id);
		}

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Used to retrieve an employee by user name.
	 *
	 * @param login The user name for which to find the employee.
	 *
	 * @return Returns the employee with the specified user name,
	 *         or null if that employee does not exist.
	 */
	public function getByLogin($login)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('lower(login) = ?', strtolower($login));

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Make sure the employee id value is valid.
		if (is_numeric($obj->id)) {
			// Set the employee's roles.
			$roleDao = new RoleDao();
			$obj->roles = $roleDao->getForEmployee($obj->id);

			// Set the employee's supervisors.
			$obj->supervisors = $this->getSupervisorEmployees($obj->id);

			// Set the employees this employee is supervising.
			$obj->supervised = $this->getSupervisedEmployees($obj->id);
		}

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Retrieve all the employee objects in the database.
	 *
	 * @return Returns an array of all the employees in the database.
	 */
	public function getAll($activeOnly = null)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name);

		// Add the active clause if necessary.
		if ($activeOnly)
			$select->where('active = true');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the identified employees.
			foreach ($objs as $obj) {
				// Make sure the employee id value is valid.
				if (is_numeric($obj->id)) {
					// Set the employee's roles.
					$roleDao = new RoleDao();
					$obj->roles = $roleDao->getForEmployee($obj->id);

					// Set the employee's supervisors.
					$obj->supervisors = $this->getSupervisorEmployees($obj->id);

					// Set the employees this employee is supervising.
					$obj->supervised = $this->getSupervisedEmployees($obj->id);
				}

				// Perform the post-processing.
				$this->postProcess($obj);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve a set of employees based on id.
	 *
	 * @param ids The array of ids for which employees will be retrieved.
	 *
	 * @return Returns the requested employees.
	 */
	public function getEmployees($ids)
	{
		// Make sure some ids were provided.
		if (!isset($ids) || count($ids) == 0)
			return array();

		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('id in (' . implode(',', $ids) . ')');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			foreach ($objs as $obj) {
				// Make sure the employee id value is valid.
				if (is_numeric($obj->id)) {
					// Set the employee's roles.
					$roleDao = new RoleDao();
					$obj->roles = $roleDao->getForEmployee($obj->id);

					// Set the employee's supervisors.
					$obj->supervisors = $this->getSupervisorEmployees($obj->id);

					// Set the employees this employee is supervising.
					$obj->supervised = $this->getSupervisedEmployees($obj->id);
				}

				// Do the employee post-processing.
				$this->postProcess($obj);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve an authenticated employee.
	 *
	 * @param login The login name for which to find the employee.
	 *
	 * @param password The password the employee is trying to login with.
	 *
	 * @return Returns the employee with the specified login or password,
	 *         or null if that employee does not exist.
	 */
	public function getAuthEmployee($login, $password)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Get the hashed version of the password.
        $hashed = hash('SHA512', $password);

		// Build the query.
		$select->from($this->_name)
			   ->where('(lower(login) = ? or lower(email) = ?)',
					   strtolower($login), strtolower($login))
			   ->where('hashed_pass = ?', $hashed)
			   ->where('active = true');

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Make sure the employee id value is valid.
		if (is_numeric($obj->id)) {
			// Set the employee's roles.
			$roleDao = new RoleDao();
			$obj->roles = $roleDao->getForEmployee($obj->id);

			// Set the employee's supervisors.
			$obj->supervisors = $this->getSupervisorEmployees($obj->id);

			// Set the employees this employee is supervising.
			$obj->supervised = $this->getSupervisedEmployees($obj->id);
		}

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Used to retrieve an employee by login.
	 *
	 * @param login The login name for which to find the employee.
	 *
	 * @return Returns the employee with the specified login,
	 *         or null if that employee does not exist.
	 */
	public function getEmployeeByLogin($login)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name)
			   ->where('(lower(login) = ? or lower(email) = ?)',
					   strtolower($login), strtolower($login));

		// Retrieve and return the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Make sure the employee id value is valid.
		if (is_numeric($obj->id)) {
			// Set the employee's roles.
			$roleDao = new RoleDao();
			$obj->roles = $roleDao->getForEmployee($obj->id);

			// Set the employee's supervisors.
			$obj->supervisors = $this->getSupervisorEmployees($obj->id);

			// Set the employees this employee is supervising.
			$obj->supervised = $this->getSupervisedEmployees($obj->id);
		}

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Used to retrieve a set of supervisors for an employee.
	 *
	 * @param id The employee id for which supervisors are to be retrieved.
	 *
	 * @return Returns the requested supervisor employees.
	 */
	public function getSupervisorEmployees($id)
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
		$supervisorDao = new SupervisorDao();

		// Build the query.
		$select->from(array('e' => $this->_name))
			   ->join(array('s' => $supervisorDao->_name),
					   's.supervisor_id = e.id', array('primary'))
			   ->where('s.employee_id = ?', $id);

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the identified employees.
			foreach ($objs as $obj) {
				// Make sure the employee id value is valid.
				if (is_numeric($obj->id)) {
					// Set the employee's roles.
					$roleDao = new RoleDao();
					$obj->roles = $roleDao->getForEmployee($obj->id);
				}

				// Perform the post-processing.
				$this->postProcess($obj);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve a set of supervised employees for a supervisor.
	 *
	 * @param id The supervisor employee id for which supervised employees
	 * are to be retrieved.
	 *
	 * @return Returns the requested supervised employees.
	 */
	public function getSupervisedEmployees($id)
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
		$supervisorDao = new SupervisorDao();

		// Build the query.
		$select->from(array('e' => $this->_name))
			   ->join(array('s' => $supervisorDao->_name),
					   's.employee_id = e.id', array('primary'))
			   ->where('s.supervisor_id = ?', $id)
			   ->where('e.active = true');

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the identified employees.
			foreach ($objs as $obj) {
				// Make sure the employee id value is valid.
				if (is_numeric($obj->id)) {
					// Set the employee's roles.
					$roleDao = new RoleDao();
					$obj->roles = $roleDao->getForEmployee($obj->id);
				}

				// Perform the post-processing.
				$this->postProcess($obj);
			}

		// Return the objects.
		return $objs;
	}

	/**
	 * Used to retrieve a set of employees assigned to a contract.
	 *
	 * @param id The contract id for which assigned employees are to be
	 * retrieved.
	 *
	 * @param day The day for which contract assignments must be valid.
	 *
	 * @return Returns the requested contract employees.
	 */
	public function getContractEmployees($id, $day = null)
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
		$select->from(array('a' => $assignmentDao->_name))
			   ->join(array('e' => $this->_name),
					   'a.employee_id = e.id',
					   array('login', 'email', 'first_name', 'last_name',
						   'suffix', 'division', 'personnel_type', 'active'))
			   ->where('a.contract_id = ?', $id)
			   ->where('e.active = true');

		// Make sure the pay period is not null before adding the
		// clause to prevent expired contracts from being retrieved.
		if (isset($day)) {
			$select->where('start IS NULL OR start <= ?', $day);
			$select->where('end IS NULL OR end >= ?', $day);
		}

		// Add ordering.
		$this->setDefaultOrder($select);

		// Retrieve all the objects.
		$objs = $db->query($select)->fetchAll();

		// Perform post-processing on all the objects.
		if (isset($objs) && count($objs) > 0)
			// Iterate over the identified employees.
			foreach ($objs as $obj) {
				// Make sure the employee id value is valid.
				if (is_numeric($obj->id)) {
					// Set the employee's roles.
					$roleDao = new RoleDao();
					$obj->roles = $roleDao->getForEmployee($obj->id);

					// Set the employee's supervisors.
					$obj->supervisors = $this->getSupervisorEmployees($obj->id);

					// Set the employees this employee is supervising.
					$obj->supervised = $this->getSupervisedEmployees($obj->id);
				}

				// Perform the post-processing.
				$this->postProcess($obj);
			}

		// Return the objects.
		return $objs;
	}
}

