<?php
defined('_JEXEC') or die;
/**
 * Eschool display helper.
 *
 * @package     E-School Management
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolHelper
{
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 * @since   1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, 'com_eschool'));
		}

		return $result;
	}
	
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 * @return  void
	 * @since   1.0
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_STUDENTS'),
			'index.php?option=com_eschool&view=students',
			$vName == 'students'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_COURSES'),
			'index.php?option=com_eschool&view=courses',
			$vName == 'courses'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_COURSEGROUPS'),
			'index.php?option=com_eschool&view=coursegroups',
			$vName == 'coursegroups'
		);

		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_CLASSLEVELS'),
			'index.php?option=com_eschool&view=classlevels',
			$vName == 'classlevels'
		);		
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_SEMESTERS'),
			'index.php?option=com_eschool&view=semesters',
			$vName == 'semesters'
		);	
				
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_REGISTRATIONS'),
			'index.php?option=com_eschool&view=registrations',
			$vName == 'registrations'
		);	
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_RAWSCORES'),
			'index.php?option=com_eschool&view=rawscores',
			$vName == 'rawscores'
		);			
	}

	
}