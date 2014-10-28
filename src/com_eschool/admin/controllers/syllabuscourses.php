<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Syllabuscourses Subcontroller.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
*/
class EschoolControllerSyllabuscourses extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the model class name.
	 * @param   string  $config  The model configuration array.
	 *
	 * @return  EschoolModelSyllabuscourses	The model for the controller set to ignore the request.
	 * @since   1.6
	 */
	public function getModel($name = 'Syllabuscourse', $prefix = 'EschoolModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}
