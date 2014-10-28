<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Syllabuscourses model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelSyllabuscourses extends JModelList
{
	/**
	 * Constructor override.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EschoolModelSyllabuscourses
	 * @since   1.0
	 * @see     JModelList
	 */

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'checked_out', 'a.checked_out',
				'a.academic_term', 'a.class_level_id',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
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
	protected function populateState($ordering = 'id', $direction = 'asc')
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.class_level_id', 'filter_class_level_id');
		$this->setState('filter.class_level_id', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.academic_term', 'filter_academic_term');
		$this->setState('filter.academic_term', $value);
				
		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.syllabus_id', 'filter_syllabus_id');
		$this->setState('filter.syllabus_id', $value);

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
				'a.id, a.syllabus_id, a.course_id, a.class_level_id, a.academic_term, a.credit, a.hours,' .
				'a.created, a.ordering'
			)
		);
		
		$query->from('#__eschool_syllabus_courses AS a');

		$query->select('sl.title as syllabus_title');
		$query->join('LEFT', '#__eschool_syllabuses AS sl ON sl.id=a.syllabus_id');
		
		$query->select('cl.title as classlevel_title');
		$query->join('LEFT', '#__eschool_classlevels AS cl ON cl.id=a.class_level_id');
		
		// Join over the categories.
		$query->select('c.access, c.language, c.title AS course_title, c.alias, c.course_code');
		$query->join('LEFT', '#__eschool_courses AS c ON c.id = a.course_id');
		
		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = c.language');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = c.access');

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
		if ($classLevel = $this->getState('filter.class_level_id')) {
			$query->where('a.class_level_id = ' . (int) $classLevel);
		}
		
		// Filter by access level.
		if ($academicTerm= $this->getState('filter.academic_term')) {
			$query->where('a.academic_term = ' . (int) $academicTerm);
		}
		
		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('c.access = ' . (int) $access);
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('c.published = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(c.published = 0 OR c.published = 1)');
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('c.language = '.$db->quote($language));
		}

		// Filter by a syllabus id.
		$syllabus_id = $this->getState('filter.syllabus_id');
		$query->where('a.syllabus_id = '.$syllabus_id);
		
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'course_title') {
			$orderCol = 'course_title '.$orderDirn.', a.ordering';
		}
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}