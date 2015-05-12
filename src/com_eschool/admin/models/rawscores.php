<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

/**
 * Rawscores model.
 *
 * @package     E-School Management
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelRawscores extends JModelList
{
	
	/**
	 * Constructor override.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EschoolModelRawscores
	 * @since   1.0
	 * @see     JModelList
	 */

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'c.title', 
				'a.exam_id','exam_title', 'a.score', 'full_score',
				'st.first_name', 'st.last_name', 'st.student_code', 
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
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
	protected function populateState($ordering = 'exam_id', $direction = 'asc')
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.student', 'filter_student');
		$this->setState('filter.student', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.exam_id', 'filter_exam_id');
		$this->setState('filter.exam_id', $value);

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
				'a.id, a.semester_id, a.student_id, a.syllabus_course_id, a.exam_id, a.score, '. 
				'a.checked_out, a.checked_out_time, a.language, ' .
				'a.published, a.access, a.created, a.ordering'
			)
		);
		$query->from('#__eschool_rawscores AS a');
		
		$query->select('e.title AS exam_title, scoring_plan_id, full_score, e.weight as exam_weight');
		$query->join('LEFT', '#__eschool_exams AS e ON e.id=a.exam_id');
		
		$query->select('p.syllabus_course_id, p.semester_id');
		$query->join('LEFT', '#__eschool_scoring_plans AS p ON p.id=e.scoring_plan_id');
		
		$query->select('s.title as semester_title, academic_year, academic_period');
		$query->join('LEFT', '#__eschool_semesters AS s ON s.id=p.semester_id');
		
		$query->select('sc.course_id');
		$query->join('LEFT', '#__eschool_syllabus_courses AS sc ON sc.id=p.syllabus_course_id');
		
		$query->select('c.title as course_title, course_code');
		$query->join('LEFT', '#__eschool_courses AS c ON c.id=sc.course_id');
		
		$query->select('st.first_name, st.last_name, st.student_code');
		$query->join('LEFT', '#__eschool_students AS st ON st.id=a.student_id');
		
		
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

		// Filter by student
		if ($student = $this->getState('filter.student')) {
			$query->where('a.student_id=' . (int) $student);
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
		$examId = $this->getState('filter.exam_id');
		if (is_numeric($examId)) {
			$query->where('a.exam_id = '.(int) $examId);
		}
		else if (is_array($examId)) {
			JArrayHelper::toInteger($examId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.exam_id IN ('.$examId.')');
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
	
	public function getExams()
	{
		$query = $this->_db->getQuery(true);
		$query->select('e.id as value, e.title as text')
		->from('#__eschool_exams AS e')
		->order('e.scoring_plan_id ASC');
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}
	
	public function getStudents()
	{
		$query = $this->_db->getQuery(true);
		$query->select('s.id, s.title, s.first_name, s.last_name, s.student_code as code')
		->from('#__eschool_students AS s')
		->order('s.first_name, s.last_name ASC');
	
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();
		$students = array();
		foreach($rows as $row) {
			$student = new stdClass();
			$student->text = EschoolHelper::getNameTitle($row->title).' '.$row->first_name.' '.$row->last_name.'('.$row->code.')';
			$student->value = $row->id;
			$students[] = $student;
		}
	
		return $students;
	
	}
}