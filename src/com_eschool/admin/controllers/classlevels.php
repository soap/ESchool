<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Classlevels Subcontroller.
 *
 * @package     ESChool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolControllerClasslevels extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * 
	 * @param   string  $name    The name of the model.
	 * @param   string  $prefix  The prefix for the model class name.
	 * @param   string  $config  The model configuration array.
	 *
	 * @return  EschoolModelClasslevels	The model for the controller set to ignore the request.
	 * @since   1.6
	 */
	public function getModel($name = 'Classlevel', $prefix = 'EschoolModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}