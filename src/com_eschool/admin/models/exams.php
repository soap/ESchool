<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Exams model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelExams extends JModelList
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
	protected function populateState($ordering = 'title', $direction = 'asc')
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
				'a.id, a.title, a.alias, a.full_score, a.weight, a.checked_out, a.checked_out_time, a.scoring_plan_id,' .
				'a.published, a.access, a.created, a.ordering, a.language'
			)
		);
		$query->from('#__eschool_exams AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the scoring plan.
		$query->select('sp.semester_id, sp.syllabus_course_id');
		$query->join('LEFT', '#__eschool_scoring_plans AS sp ON sp.id = a.scoring_plan_id');
		
		$query->select('sm.title as semester_title');
		$query->join('LEFT', '#__eschool_semesters AS sm ON sm.id=sp.semester_id');

		$query->select('st.title as scoring_type_title');
		$query->join('LEFT', '#__eschool_scoring_types AS st ON st.id=sp.scoring_type_id');
		
		$query->join('LEFT', '#__eschool_syllabus_courses AS sy ON sy.id=sp.syllabus_course_id');
		
		$query->select('c.title as course_title');
		$query->join('LEFT', '#__eschool_courses AS c on c.id=sy.course_id');
		

		// Join over the users for the author.
		$query->select('ua.name AS author_name');
		$query->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'a.scoring_plan_id') {
			$orderCol = 'a.scoring_plan_id '.$orderDirn.', a.ordering';
		}
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
}