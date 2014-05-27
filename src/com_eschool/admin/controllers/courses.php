<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Courses Subcontroller.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolControllerCourses extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * 
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the model class name.
	 * @param   string  $config  The model configuration array.
	 *
	 * @return  EschoolModelCourses	The model for the controller set to ignore the request.
	 * @since   1.6
	 */
	public function getModel($name = 'Course', $prefix = 'EschoolModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}