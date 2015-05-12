<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Scoringplans model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelScoringplans extends JModelList
{
	/**
	 * Constructor override.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EschoolModelScoringplans
	 * @since   1.0
	 * @see     JModelList
	 */

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'semester_title', 'course_title', 'syllabus_title',
				'a.scoring_type_id', 'scoring_type_title',
				'a.weight',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
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
	protected function populateState($ordering = 'a.semester_id', $direction = 'asc')
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.syllabus_id', 'filter_syllabus_id', 0, 'int');
		$this->setState('filter.syllabus_id', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.semester_id', 'filter_semester_id', 0, 'int');
		$this->setState('filter.semester_id', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.syllabus_course_id', 'filter_syllabus_course_id');
		$this->setState('filter.syllabus_course_id', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $value);

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
				'a.id, a.semester_id, a.syllabus_course_id, ' . 
				'a.weight, a.scoring_type_id, ' .
				'a.checked_out, a.checked_out_time, ' .
				'a.published, a.access, a.created, a.ordering, a.language'
			)
		);
		$query->from('#__eschool_scoring_plans AS a');
		
		$query->select('sc.course_id, sc.syllabus_id');
		$query->join('LEFT', '#__eschool_syllabus_courses AS sc ON sc.id=a.syllabus_course_id');
		
		$query->select('se.title as semester_title');
		$query->join('LEFT', '#__eschool_semesters AS se ON se.id=a.semester_id');

		$query->select('sy.title as syllabus_title');
		$query->join('LEFT', '#__eschool_syllabuses AS sy ON sy.id=sc.syllabus_id');
				
		$query->select('c.title as course_title');
		$query->join('LEFT', '#__eschool_courses AS c ON c.id=sc.course_id');

		$query->select('st.title as scoring_type_title');
		$query->join('LEFT', '#__eschool_scoring_types AS st ON st.id=a.scoring_type_id');
		
		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

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
				$query->where('(c.title LIKE '.$search.' OR c.alias LIKE '.$search.')');
			}
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int) $access);
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by a single or group of categories.
		$value = (int)$this->getState('filter.syllabus_id');
		if ($value > 0) {
			$query->where('a.syllabus_id= '.(int) $value);
		}
		
		$value = (int)$this->getState('filter.semester_id');
		if ($value > 0) {
			$query->where('a.semester_id= '.(int) $value);
		}
		
		$value = (int)$this->getState('filter.syllabus_course_id');
		if ($value > 0) {
			$query->where('a.syllabus_course_id= '.(int) $value);
		}
		
		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = '.$db->quote($language));
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'semester_title') {
			$orderCol = 'semester_title '.$orderDirn.',a.syllabus_course_id '.$orderDirn.',course_title '.$orderDirn.', a.ordering';
		}
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}