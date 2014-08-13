<?php
defined('_JEXEC') or die;
jimport('joomla.database.table');

/**
 * Student table.
 *
 * @package     E-School Management
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolTableStudent extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param   JDatabase  $db  A database connector object.
	 *
	 * @return  EschoolTableStudent
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__eschool_students', 'id', $db);
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array   $array   The input array to bind.
	 * @param   string  $ignore  A list of fields to ignore in the binding.
	 *
	 * @return  null|string	null is operation was satisfactory, otherwise returns an error
	 * @see     JTable:bind
	 * @since   1.0
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return  boolean  True on success.
	 * @since   1.0
	 */
	public function check()
	{
		// Check for valid name.
		if (trim($this->first_name) === '') {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_FIRST_NAME'));
			return false;
		}
		
		if (trim($this->last_name) === '') {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_LAST_NAME'));
			return false;
		}
		
		if (trim($this->student_code) === '') {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_STUDENT_CODE'));
			return false;
		}
		
		if (trim($this->entry_date) === '') {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_ENTRY_DATE'));
			return false;
		}
		return true;
	}

	/**
	 * Overload the store method for the Weblinks table.
	 *
	 * @param   boolean  $updateNulls  Toggle whether null values should be updated.
	 *
	 * @return  boolean  True on success, false on failure.
	 * @since   1.0
	 */
	public function store($updateNulls = false)
	{
		// Initialiase variables.
		$date	= JFactory::getDate()->toMySQL();
		$userId	= JFactory::getUser()->get('id');

		if (empty($this->id)) {
			// New record.
			$this->created		= $date;
			$this->created_by	= $userId;
		} 
		else {
			// Existing record.
			$this->modified	= $date;
			$this->modified_by	= $userId;
		}

		// Attempt to store the data.
		return parent::store($updateNulls);
	}
}
