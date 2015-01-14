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
	
	function display($cachable = false, $urlparams = false)
	{
		// Load the component helper.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/eschool.php';

		// Display the view.
		parent::display($cachable, $urlparams);
	}
}