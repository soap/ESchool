<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Grades model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelGrades extends JModelList
{
	/**
	 * Constructor override.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @return  EschoolModelGrades
	 * @since   1.0
	 * @see     JModelList
	 */

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'fullname', 'full_name',
				'alias', 'a.alias', 
				'student_id',
				'grading_id', 'syllabus_course',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'semester_title', 'a.scoring_progress', 'a.scoring_percent',
				'course_title', 'g.pointing', 'g.title',
				'published', 'a.state', 'a.published',
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
	protected function populateState($ordering = 'registration_id', $direction = 'asc')
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.student', 'filter_student', '');
		$this->setState('filter.student', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.regsemester', 'filter_regsemester', '');
		$this->setState('filter.regsemester', $value);

		$value = $app->getUserStateFromRequest($this->context.'.filter.syllabus_course', 'filter_syllabus_course', '');
		$this->setState('filter.syllabus_course', $value);
		
		$value = $app->getUserStateFromRequest($this->context.'.filter.grading', 'filter_grading', '');
		$this->setState('filter.grading', $value);
		
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
				'a.id, a.semester_id, a.syllabus_course_id, a.scoring_progress, a.scoring_percent, a.grading_id, ' .
				'a.checked_out, a.checked_out_time, ' .
				'a.state, a.access, a.created, a.ordering, a.language'
			)
		);
		$query->from('#__eschool_registration_records AS a');

		$query->select('g.grade as scoring_grade, g.pointing');
		$query->join('LEFT', '#__eschool_gradings AS g ON g.id=a.grading_id');
		
		// Join over student
		$query->join('LEFT', '#__eschool_registrations AS r ON r.id=a.registration_id');
		$query->select('s.id AS student_id,s.title AS title, s.first_name AS first_name, s.last_name AS last_name, s.student_code AS student_code');
		
		$query->join('LEFT', '#__eschool_students AS s ON s.id=r.student_id');		

		$query->select('sm.title as semester_title, sm.academic_year, sm.academic_period');
		$query->join('LEFT', '#__eschool_semesters AS sm ON sm.id=r.semester_id');
		
		$query->select('sc.course_id');
		$query->join('LEFT', '#__eschool_syllabus_courses AS sc ON sc.id=a.syllabus_course_id');
		
		// Join over course
		$query->select('c.title as course_title, c.course_code as course_code');
		$query->join('LEFT', '#__eschool_courses AS c ON c.id=sc.course_id');
				
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
			} else if (stripos($search, 'name:') === 0) {
				$search = $db->quote('%'.$db->getEscaped(substr($search, 5), true).'%');
				$query->where('(s.first_name LIKE '.$search.' OR s.last_name LIKE '.$search.')');
			} else if (stripos($search, 'course:') === 0) {
				$search = $db->quote('%'.$db->getEscaped(substr($search, 7), true).'%');
				$query->where('c.title LIKE '.$search);
			}
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int) $access);
		}
		
		// Filter by syllabus_course
		if ($student = $this->getState('filter.student')) {
			$query->where('r.student_id=' . (int) $student);
		}
		
		// Filter by grading level A, B, C, ....
		if ($grading = $this->getState('filter.grading')) {
			$query->where('a.grading_id = ' . (int) $grading);
		}
		
		// Filter by syllabus_course
		if ($syllabusCourse = $this->getState('filter.syllabus_course')) {
			$query->where('a.syllabus_course_id = ' . (int) $syllabusCourse);
		}
		
		// Filter by register semester.
		if ($regSemester = (int)$this->getState('filter.regsemester')) {
			$query->where('a.semester_id = ' . (int) $regSemester);
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by a single or group of categories.
		$courseId = $this->getState('filter.course_id');
		if (is_numeric($courseId)) {
			$query->where('a.course_id = '.(int) $courseId);
		}
		else if (is_array($courseId)) {
			JArrayHelper::toInteger($courseId);
			$courseId = implode(',', $courseId);
			$query->where('a.syllabus_course_id IN ('.$courseId.')');
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = '.$db->quote($language));
		}
				
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		if ($orderCol == 'fullname' || $orderCol == 'full_name') {
			$query->order('s.first_name '.$orderDirn, ',s.last_name '.$orderDirn);
		}else{
			$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		}

		return $query;
	}

	public function getGradings()
	{
		$query = $this->_db->getQuery(true);
		$query->select('id AS value, title AS text')
			->from('#__eschool_gradings')
			->where('published=1')
			->order('pointing DESC');
		
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	public function getCourses()
	{
		$query = $this->_db->getQuery(true);
		$query->select('sc.id AS value, c.title AS text')
		->from('#__eschool_syllabus_courses AS sc')
		->join('LEFT', '#__eschool_courses AS c ON c.id=sc.course_id')
		->where('published=1')
		->order('c.title ASC');
	
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