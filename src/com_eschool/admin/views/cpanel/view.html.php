<?php
/**
 * version: $Id: view.html.php 68 2012-01-11 07:47:13Z gprasit $
 * package: ASTERman
 * subpackage:
 */
// no direct access
defined( '_JEXEC' ) or die;
//ensure that we have JView
jimport('joomla.application.component.view');

class EschoolViewCpanel extends JView
{
	
	protected $version;
	
	function display($tpl=null) 
	{
        JHtml::stylesheet( 'administrator/components/com_asterman/assets/css/eschool.css' );
		JHTML::_('behavior.tooltip');
		
        $this->version = EschoolHelper::getVersion();
		$this->addToolbar();
		parent::display($tpl);
    }
    
    function addToolBar() 
    {
    	JFactory::getDocument()->setTitle(JText::_("COM_ESCHOOL_CPANEL_TITLE"));
		JToolBarHelper::title( JText::_("COM_ESCHOOL_CPANEL_TITLE"));
        
		$user = JFactory::getUser();
		if ($user->authorise('core.admin', 'com_eschool')) {
			JToolBarHelper::preferences( 'com_eschool', '500','600' );
		}
	}

}