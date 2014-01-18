<?php
defined('_JEXEC') or die;

/**
 * Eschool Component Controller
 *
 * @package     E-School Management
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolController extends JControllerLegacy
{
		/**
	 * Override the display method for the controller.
	 *
	 * @return  void
	 * @since   1.0
	 */
	
	protected $default_view = 'students';
	
	function display($cachable = false, $urlparams = false)
	{
		// Load the component helper.
		require_once JPATH_COMPONENT.'/helpers/eschool.php';
		
		// Load the submenu.
		$view = JRequest::getCmd('view', $this->default_view);
		EschoolHelper::addSubmenu($view);

		// Display the view.
		parent::display($cachable, $urlparams);
	}
}