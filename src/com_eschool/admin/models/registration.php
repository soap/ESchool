<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

/**
 * Registration model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelRegistration extends JModelAdmin
{
	/**
	 * Method to get the Registration form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			$this->option.'.'.$this->name,
			$this->getName(),
			array('control' => 'jform', 'load_data' => $loadData)
		);

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get a Registration.
	 *
	 * @param   integer  $pk  An optional id of the object to get, otherwise the id from the model state is used.
	 *
	 * @return  mixed    Category data object on success, false on failure.
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($result = parent::getItem($pk)) {

			// Convert the created and modified dates to local user time for display in the form.
			jimport('joomla.utilities.date');
			$tz	= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));

			if (intval($result->created)) {
				$date = new JDate($result->created);
				$date->setTimezone($tz);
				$result->created = $date->toSql(true);
			}
			else {
				$result->created = null;
			}

			if (intval($result->modified)) {
				$date = new JDate($result->modified);
				$date->setTimezone($tz);
				$result->modified = $date->toSql(true);
			}
			else {
				$result->modified = null;
			}
		}

		return $result;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   JTable  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 * @since   1.0
	 */
	protected function getReorderConditions($table = null)
	{
		$condition = array(
			'symester_id = '.(int) $table->semester_id,
			'syllabus_id = '.(int) $table->syllabus_id,
			'student_id = '.(int) $table->student_id
		);

		return $condition;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name.
	 * @param   array   $config  Configuration array for model.
	 *
	 * @return  JTable  A database object
	 * @since   1.0
	 */
	public function getTable($type = 'Registration', $prefix = 'EschoolTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState($this->option.'.edit.'.$this->getName().'.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/** 
	 * Prepare the form before display to the user.
	 *
	 * @param   JForm  $form  The table object for the record.
	 * @param   	   $data  The data binded to $form
	 * @param	string $group group of plugin to trigger
	 * @return  boolean  True if successful, otherwise false and the error is set.
	 * @since   1.0
	 */	 
	 
	protected function preprocessForm(JForm $form, $data, $group='content')
	{
		$user = JFactory::getUser();
		$statesFields = array('state');
		if ( !($user->authorise('core.edit.state', 'com_eschool')) ) {
			foreach($stateFields as $field) {
				$form->setFieldAttribute($field, 'disabled', 'true');
				$form->setFieldAttribute($field, 'filter', 'unset');
			}
		}
		
		parent::preprocessForm($form, $data, $group);
	}
	
	/** 
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  The table object for the record.
	 *
	 * @return  boolean  True if successful, otherwise false and the error is set.
	 * @since   1.0
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		// Prepare the alias.
		$table->alias = JApplication::stringURLSafe($table->alias);
		if (empty($table->book_number)) {
			$table->book_number = date("y");

			// Set ordering to the last item if not set
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			$query->select('MAX(doc_number)');
			$query->from('#__eschool_registrations');
			$query->where('book_number = '.(int) $table->book_number);

			$max = (int) $db->setQuery($query)->loadResult();
				
			if ($error = $db->getErrorMsg()) {
				$this->setError($error);
				return false;
			}

			$table->doc_number = $max + 1;
		}
		// If the alias is empty, prepare from the value of the title.
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->semester_id.'-'.$table->syllabus_id.'-'.$table->student_id);
		}

		if (empty($table->id)) {
			// For a new record.

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true);
				$query->select('MAX(ordering)');
				$query->from('#__eschool_registrations');
				$query->where('semester_id = '.(int) $table->semester_id);
				$query->where('syllabus_id = '.(int) $table->syllabus_id);
				$query->where('student_id = '.(int) $table->student_id);
				
				$max = (int) $db->setQuery($query)->loadResult();
				
				if ($error = $db->getErrorMsg()) {
					$this->setError($error);
					return false;
				}

				$table->ordering = $max + 1;
			}
		}

		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey)) {
			// Only process if not empty.

			// array of characters to remove.
			$strip = array("\n", "\r", '"', '<', '>');
			
			// Remove bad characters.
			$clean = JString::str_ireplace($strip, ' ', $this->metakey); 

			// Create array using commas as delimiter.
			$oldKeys = explode(',', $clean);
			$newKeys = array();
			
			foreach ($oldKeys as $key)
			{
				// Ignore blank keywords
				if (trim($key)) {
					$newKeys[] = trim($key);
				}
			}

 			// Put array back together, comma delimited.
 			$this->metakey = implode(', ', $newKeys);
		}
	}
	
	public function save($data) 
	{
		if (parent::save($data)) {
			$id = (int)$this->getState($this->getName().'.id');
			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('semester_id, syllabus_id, student_id, class_level_id')
				->from('#__eschool_registrations')
				->where('id='.$id);
			$db->setQuery($query);
			$regData = $db->loadObject();
			
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eschool_registration_records')
				->where('registration_id = '.$id);
			$db->setQuery($query);
			
			if ($db->loadResult() == 0) {
				$query->clear();
				$query->select('academic_year, academic_period')
					->from('#__eschool_semesters')
					->where('id='.(int)$regData->semester_id);
				$db->setQuery($query);
				
				$semesterData = $db->loadObject();
				if ($semesterData == NULL) {
					return true;
				}
				
				$query->clear();
				$query->select('id')
					->from('#__eschool_syllabus_courses')
					->where('syllabus_id='.$regData->syllabus_id)
					->where('class_level_id='.$regData->class_level_id)
					->where('academic_term='.$semesterData->academic_period);				
				$db->setQuery($query);
				
				$rows = $db->loadObjectList();
				if ($rows == NULL) {
					echo $db->getQuery();
					echo $db->getErrorMsg();
					jexit();
				}
				$data = array(
						'registration_id'=>$id, 'semester_id'=>$regData->semester_id, 'syllabus_id'=>$regData->syllabus_id,
						'state'=>1
				);
				
				foreach($rows as $row) {
					$data['syllabus_course_id'] = $row->id;
					$table = JTable::getInstance('Registrationrecord', 'EschoolTable');
					$table->bind($data);
					$table->check();
					$table->store();
				}			
			}
			
			return true;	
		}else{
			return false;
		}
	}
}