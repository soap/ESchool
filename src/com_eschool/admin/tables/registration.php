<?php
defined('_JEXEC') or die;
jimport('joomla.database.table');

/**
 * Registration table.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolTableRegistration extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param   JDatabase  $db  A database connector object.
	 *
	 * @return  EschoolTableRegistration
	 * @since   1.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__eschool_registrations', 'id', $db);
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
		// Check for valid semester		
		if (empty($this->semester_id)) {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_SEMESTER_ID'));
			return false;
		}

		// Check for valid semester
		if (empty($this->syllabus_id)) {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_SYLLABUS_ID'));
			return false;
		}

		// Check for valid semester
		if (empty($this->student_id)) {
			$this->setError(JText::_('COM_ESCHOOL_ERROR_STUDENT_ID'));
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
		$date	= JFactory::getDate()->toSql();
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