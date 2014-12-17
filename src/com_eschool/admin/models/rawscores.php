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

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}