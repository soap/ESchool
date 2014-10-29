<?php 
/**
 *
 * @version     $Id: cpanel.php 1452 2009-05-19 16:15:22Z prasit gebsaap$
 * @package     ASTERman
 * @copyright   Copyright (C)  2008-2009 Prasit Gebsaap
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.joomlant.com
 */
defined('_JEXEC') or die('Restricted access'); 
$user = JFactory::getUser();
?>
<form name="adminForm" id="adminForm" action="index.php?option=com_eschool" method="post" >
<div class="adminform">
    <div class="cpanel-left">
	   <div id="cpanel">
<?php
		// User has globally manage permission
		if ($user->authorise('core.admin')) {
			$link = "index.php?option=com_eschool&view=syllabuses"; 
			echo EschoolHelper::quickIconButton($link,  'eschool_syllabus_48.png', JText::_( 'COM_ESCHOOL_SYLLABUSES' ) );
			$link = "index.php?option=com_eschool&view=students";
        	echo EschoolHelper::quickIconButton($link,  'eschool_student_48.png', JText::_( 'COM_ESCHOOL_STUDENTS' ) );
		}
		$link = "index.php?option=com_eschool&view=registrations";
		echo EschoolHelper::quickIconButton($link,  'eschool_registration_48.png', JText::_( 'COM_ESCHOOL_REGISTRATIONS' ) );
		
		$link = "index.php?option=com_eschool&view=scores";
		echo EschoolHelper::quickIconButton($link,  'eschool_score_48.png', JText::_( 'COM_ESCHOOL_SCORES' ) );
		
		
?>		
            <div style="clear:both">&nbsp;</div>
            <p>&nbsp;</p>
            <div style="text-align:center;padding:0;margin:0;border:0"></div>
	   </div>
    </div>
		
    <div class="cpanel-right">
	   <div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
            <div style="float:right;margin:10px;">
	           <img src="<?php echo JURI::root(true)?>/media/com_eschool/images/joomlant_logo.png" alt="joomlant.org">
            </div>
<?php
		echo '<h3>'.  JText::_('COM_ESCHOOL_VERSION').'</h3>'
		.'<p>'.  $this->version .'</p>';

		echo '<h3>'.  JText::_('COM_ESCHOOL_COPYRIGHT').'</h3>'
		.'<p>© 2009 - '.  date("Y"). ' Prasit Gebsaap</p>'
		.'<p><a href="http://www.joomlant.org/" target="_blank">www.www.joomlant.org</a></p>';

		echo '<h3>'.  JText::_('COM_ESCHOOL_LICENSE').'</h3>'
		.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';
		
		echo '<h3>'.  JText::_('COM_ESCHOOL_TRANSLATION').': '. JText::_('COM_ESCHOOL_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2009 - '.  date("Y"). ' '. JText::_('COM_ESCHOOL_TRANSLATOR'). '</p>'
        .'<p>'.JText::_('COM_ESCHOOL_TRANSLATION_SUPPORT_URL').'</p>';
?>
	   </div>
    </div>
</div>
<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>
