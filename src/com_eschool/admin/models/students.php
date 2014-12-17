<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Students model.
 *
 * @package     E-School Management
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelStudents extends JModelList
{
	/**
	 * Constructor override.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EschoolModelStudents
	 * @since   1.0
	 * @see     JModelList
	 */

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title', 'full_name',
				'alias', 'a.alias', 'syllabus_title',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'classlevel_id', 'a.classlevel_id', 'classlevel_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'language', 'a.language',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'modified_by', 'a.modified_by',
			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 * @since   1.0
	 */
	protected function populateState($ordering = 'first_name', $direction = 'asc')
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.classlevel_id', 'filter_classlevel_id');
		$this->setState('filter.classlevel_id', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $value);

		
		// Set list state ordering defaults.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 * @since   1.0
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.first_name, a.last_name, a.student_code, a.entry_date, ' .
				'a.title, CONCAT_WS(\' \',a.first_name, a.last_name) AS full_name, ' .
				'a.state, a.classlevel_id, ' .
				'a.checked_out, a.checked_out_time, a.access, a.created, a.ordering'
			)
		);
		$query->from('#__eschool_students AS a');

		$query->select('us.username');
		$query->join('LEFT', '#__users AS us ON us.id=a.user_id');
		
		$query->select('cl.title as classlevel_title');
		$query->join('LEFT', '#__eschool_classlevels AS cl ON cl.id=a.classlevel_id');
		
		$query->select('s.title AS syllabus_title');
		$query->join('LEFT', '#__eschool_syllabuses AS s ON s.id=a.syllabus_id');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.first_name LIKE '.$search.' OR a.last_name LIKE '.$search.')');
			}
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int) $access);
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by a single or group of categories.
		$classlevelId = $this->getState('filter.classlevel_id');
		if (is_numeric($classlevelId)) {
			$query->where('a.classlevel_id = '.(int) $classlevelId);
		}
		else if (is_array($classlevelId)) {
			JArrayHelper::toInteger($classlevelId);
			$categoryId = implode(',', $classlevelId);
			$query->where('a.classlevel_id IN ('.$classlevelId.')');
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = '.$db->quote($language));
		}


		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}