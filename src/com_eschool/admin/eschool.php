<?php
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_eschool')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register JHtml subclass
JLoader::registerPrefix('J', JPATH_COMPONENT.'/helpers/libraries');
// Include dependencies
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('eschool');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();