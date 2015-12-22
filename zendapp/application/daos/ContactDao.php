<?php

class ContactDao extends BaseDao
{
	/**
	 * The name of the associated database table.
	 */
	public $_name = 'contacts';

	/**
	 * Apply the necessary order calls to the provided select statement.
	 *
	 * @param select The select object to invoke order on.
	 */
	public function setDefaultOrder($select)
	{
		// Set the order.
		$select->order('company_name')
			   ->order('poc_name')
			   ->order('id');
	}

	/**
	 * Perform post-processing on the records retrieved from the database.
	 *
	 * @param obj The database object to perform processing on.
	 */
	public function postProcess($obj)
	{
		// Set the description value.
		if (isset($obj) && isset($obj->poc_name) && isset($obj->company_name))
			$obj->description = $obj->poc_name . ' (' . $obj->company_name . ')';
	}
}

