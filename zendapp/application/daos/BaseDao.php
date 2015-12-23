<?php

class BaseDao extends Zend_Db_Table
{
	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// No ordering by default.
	}

	/**
	 * Perform post-processing on the records retrieved from the database.
	 *
	 * @param obj The database object to perform processing on.
	 */
	public function postProcess($obj)
	{
		// No post-processing is performed by default.
	}

	/**
	 * Retrieve a single object.
	 *
	 * @param id The unique identifier for the object.
	 *
	 * @return Returns the requested object from the database.
	 */
	public function get($id)
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
		$select->from($this->_name)
			   ->where('id = ?', $db->quote($id, 'INTEGER'));

		// Retrieve the requested object.
		$obj = $db->query($select)->fetchObject();

		// Make sure an object was found.
		if (!isset($obj) || !isset($obj->id))
			return null;

		// Perform post-processing.
		$this->postProcess($obj);

		// Return the object retrieved.
		return $obj;
	}

	/**
	 * Retrieve all the objects in the database.
	 *
	 * @return Returns an array of all the objects in the database.
	 */
	public function getAll()
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Set the fetch mode.
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		// Get the select object.
		$select = $db->select();

		// Build the query.
		$select->from($this->_name);

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
	 *
	 * @return Returns the auto-generated id of the inserted row.
	 */
	public function add($arr)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Insert the object into the database.
		$db->insert($this->_name, $arr);

		// Return the last insert id.
		return $db->lastInsertId();
	}

	/**
	 * Remove the objects with the specified ids from the database.
	 *
	 * @param ids The ids of the objects to delete.
	 *
	 * @return Returns the number of rows removed.
	 */
	public function remove($ids)
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
					// Delete the object with the specified id.
					$count += $this->delete($db->quoteInto('id = ?', $id));

		// Return the count.
		return $count;
	}

	/**
	 * Deactivate the objects with the specified ids in the database.
	 *
	 * @param ids The ids of the objects to deactivate.
	 *
	 * @return Returns the number of rows deactivated.
	 */
	public function deactivate($ids)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the deactivated rows.
		$count = 0;

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id))
					// Update the object in the database.
					$count += $db->update($this->_name,
							array('active' => 0), "id = $id");

		// Return the count.
		return $count;
	}

	/**
	 * Activate the objects with the specified ids in the database.
	 *
	 * @param ids The ids of the objects to activate.
	 *
	 * @return Returns the number of rows activated.
	 */
	public function activate($ids)
	{
		// Get the database adapter.
		$db = $this->getAdapter();

		// Keep track of the activated rows.
		$count = 0;

		// Make sure some ids were specified.
		if (isset($ids) && count($ids) > 0)
			// Iterate over the provided ids.
			foreach ($ids as $id)
				// Make sure the id is valid.
				if (is_numeric($id))
					// Update the object in the database.
					$count += $db->update($this->_name,
							array('active' => 1), "id = $id");

		// Return the count.
		return $count;
	}

	/**
	 * Update the object represented by the provided id with the provided array
	 * of values.
	 *
	 * @param id The id of the object to modify.
	 *
	 * @param arr The array of new values.
	 */
	public function save($id, $arr)
	{
		// Make sure the id is valid.
		if (isset($id) && is_numeric($id)) {
			// Get the database adapter.
			$db = $this->getAdapter();

			// Update the object in the database.
			$db->update($this->_name, $arr, $db->quoteInto('id = ?', $id));
		}
	}
}

