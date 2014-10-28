<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Syllabuscourse Subcontroller.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolControllerSyllabuscourse extends JControllerForm
{
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		
		$app = JFactory::getApplication();
		$syllabus_id = $app->input->getCmd('filter_syllabus_id');
		if ($syllabus_id) {
			$append .= '&syllabus_id='.$syllabus_id;
		}
		return $append;
	}	
}