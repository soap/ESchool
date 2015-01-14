<?php
defined('_JEXEC') or die;

// Register JHtml subclass
JLoader::registerPrefix('J', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/libraries');
// Include dependencies
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('eschool');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();