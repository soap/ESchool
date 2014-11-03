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
			JText::_('COM_ESCHOOL_SUBMENU_CPANEL'),
			'index.php?option=com_eschool&view=cpanel',
			$vName == 'cpanel'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_COURSEGROUPS'),
			'index.php?option=com_eschool&view=coursegroups',
			$vName == 'coursegroups'
		);
				
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_COURSES'),
			'index.php?option=com_eschool&view=courses',
			$vName == 'courses'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_SYLLABUSES'),
			'index.php?option=com_eschool&view=syllabuses',
			$vName == 'syllabuses'
		);
		
		if ($vName == 'syllabuscourses') {
			JSubMenuHelper::addEntry(
				JText::_('COM_ESCHOOL_SUBMENU_SYLLABUSCOURSES'),
				'index.php?option=com_eschool&view=syllabuscourses',
				true
			);
		}

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
			JText::_('COM_ESCHOOL_SUBMENU_CLASSROOMS'),
			'index.php?option=com_eschool&view=classrooms',
			$vName == 'classrooms'
		);
						
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_STUDENTS'),
			'index.php?option=com_eschool&view=students',
			$vName == 'students'
		);
						
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_REGISTRATIONS'),
			'index.php?option=com_eschool&view=registrations',
			$vName == 'registrations'
		);	

		if ($vName == 'registrationrecords') {
			JSubMenuHelper::addEntry(
				JText::_('COM_ESCHOOL_SUBMENU_REGISTRATION_RECORDS'),
				'index.php?option=com_eschool&view=registrationrecords',
				true
			);
		}
				
		JSubMenuHelper::addEntry(
			JText::_('COM_ESCHOOL_SUBMENU_RAWSCORES'),
			'index.php?option=com_eschool&view=rawscores',
			$vName == 'rawscores'
		);			
	}
	
	public static function getCourseTypeTitle($value)
	{
		$result = JText::_("COM_ESCHOOL_OPTION_UNKNOWN");
		switch ($value) {
			case 1 : $result = JText::_("COM_ESCHOOL_OPTION_BASIC");
				break;
			case 2 : $result = JText::_("COM_ESCHOOL_OPTION_ADDITIONAL");
				break;
			case 3 : $result = JText::_("COM_ESCHOOL_OPTION_ACTIVITY");
				break;
		}
		
		return $result;
	}
	
	public function getRegDocNumber($book_number, $doc_number)
	{
		$prefix = 'REG';
		$runningDigits = 4;
		
		return $book_number.str_pad($doc_number, $runningDigits, '0', STR_PAD_LEFT);
	}
	
	static function quickiconButton( $link, $image, $text )
	{
		$lang		= JFactory::getLanguage();
		$application =  JFactory::getApplication();
		$template	= $application->getTemplate();
		$attribs = array();
		$float = ($lang->isRTL()) ? 'right' : 'left';
		
		$html = array();
		$html[] = '<div style="float:'.$float.'">';
		$html[] = '<div class="icon">';
		$html[] = '<a href="'.$link.'">';
		$html[] = JHtml::_('image', 'com_eschool/'.$image, $text, $attribs, true, false);
		$html[] = '<span>'.$text.'</span></a>';
		$html[] = '</div>';
		$html[] = '</div>';
		
		return implode("\n", $html);
	}
	 	
	static function getVersion()
	{
		$manifest = JFactory::getXML(JPATH_COMPONENT_ADMINISTRATOR.'/eschool.xml' );
		return $manifest->version;
	}
}