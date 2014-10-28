<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Syllabuses model.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolModelSyllabuses extends JModelList
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
		
		$query2 = $db->getQuery(true);
		$query3 = $db->getQuery(true);
		
		$query2->select('COUNT(*)')
			->from('#__eschool_syllabus_courses as sc')
			->where('sc.syllabus_id = a.id');
		
		$query3->select('COALESCE(SUM(sc2.credit),0)')
			->from('#__eschool_syllabus_courses AS sc2')
			->where('sc2.syllabus_id = a.id');
		
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.credit, a.checked_out, a.checked_out_time,' .	
				'a.published, a.access, a.created, a.ordering, a.language, ' .
				'('.$query2.') AS total_courses, ' .
				'('.$query3.') AS total_credits ' 
			)
		);
		
		$query->from('#__eschool_syllabuses AS a');

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
	
	public function getItems()
	{
		$items = parent::getItems();
		if ($items === false) return false;
		
		return $items;	
	}

}