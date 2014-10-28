<?php
/**
* @package      ESchool
* @subpackage   com_eschool
*
* @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
**/

defined('_JEXEC') or die();


jimport('joomla.application.component.helper');


/**
 * Utility class for Eschool javascript behaviors
 *
 */
abstract class JHtmlScript
{
    /**
     * Array containing information for loaded files
     *
     * @var    array    $loaded
     */
    protected static $loaded = array();


    /**
     * Method to load jQuery JS
     *
     * @return    void
     */
    public static function jQuery()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        $params = JComponentHelper::getParams('com_eschool');

        if (JFactory::getApplication()->isSite()) {
            $load = $params->get('jquery_site');
        }
        else {
            $load = $params->get('jquery_admin');
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html' && $load != '0') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptjQuery');
        }

        self::$loaded[__METHOD__] = true;
    }


    /**
     * Method to load jQuery UI JS
     *
     * @return    void
     */
    public static function jQueryUI()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQuery'])) {
            self::jQuery();
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptjQueryUI');
        }

        self::$loaded[__METHOD__] = true;
    }


    /**
     * Method to load jQuery Sortable JS
     *
     * @return    void
     */
    public static function jQuerySortable()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQueryUI'])) {
            self::jQueryUI();
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptjQuerySortable');
        }

        self::$loaded[__METHOD__] = true;
    }


    /**
     * Method to load jQuery Chosen JS
     *
     * @return    void
     */
    public static function jQueryChosen()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQuery'])) {
            self::jQuery();
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptjQueryChosen');
        }

        self::$loaded[__METHOD__] = true;
    }


    /**
     * Method to load bootstrap JS
     *
     * @return    void
     */
    public static function bootstrap()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQuery'])) {
            self::jQuery();
        }

        $params = JComponentHelper::getParams('com_Eschool');

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html' && $params->get('bootstrap_js') != '0') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptBootstrap');
        }

        self::$loaded[__METHOD__] = true;
    }


    /**
     * Method to load jQuery flot JS
     *
     * @return    void
     */
    public static function flot()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQuery'])) {
            self::jQuery();
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptFlot');
        }

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Method to load Eschool form JS
     *
     * @return    void
     */
    public static function form()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load dependencies
        if (empty(self::$loaded['jQuery'])) {
            self::jQuery();
        }

        if (empty(self::$loaded['eschool'])) {
            self::eschool();
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptForm');
        }

        self::$loaded[__METHOD__] = true;
    }

    /**
     * Method to load Eschool base JS
     *
     * @return    void
     */
    public static function eschool()
    {
        // Only load once
        if (!empty(self::$loaded[__METHOD__])) {
            return;
        }

        // Load only of doc type is HTML
        if (JFactory::getDocument()->getType() == 'html') {
            $dispatcher	= JDispatcher::getInstance();
            $dispatcher->register('onBeforeCompileHead', 'triggerEschoolScriptCore');
        }

        self::$loaded[__METHOD__] = true;
    }
}


/**
 * Stupid but necessary way of adding jQuery to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that jQuery is not already loaded
 *
 */
function triggerEschoolScriptjQuery()
{
    $params = JComponentHelper::getParams('com_eschool');

    if (JFactory::getApplication()->isSite()) {
        $load = $params->get('jquery_site');
    }
    else {
        $load = $params->get('jquery_admin');
    }

    // Auto-load
    if ($load == '') {
        $scripts = (array) array_keys(JFactory::getDocument()->_scripts);
        $string  = implode('', $scripts);

        if (stripos($string, 'jquery') === false) {
            JHtml::_('script', 'com_eschool/jquery/jquery.min.js', false, true, false, false, false);
            JHtml::_('script', 'com_eschool/jquery/jquery.noconflict.js', false, true, false, false, false);
        }
    }

    // Force load
    if ($load == '1') {
        JHtml::_('script', 'com_eschool/jquery/jquery.min.js', false, true, false, false, false);
        JHtml::_('script', 'com_eschool/jquery/jquery.noconflict.js', false, true, false, false, false);
    }
}


/**
 * Stupid but necessary way of adding jQuery UI to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that flot is loaded after jQuery
 *
 */
function triggerEschoolScriptjQueryUI()
{
    $scripts = (array) array_keys(JFactory::getDocument()->_scripts);
    $string  = implode('', $scripts);

    if (stripos($string, 'jquery.ui') === false) {
        JHtml::_('script', 'com_eschool/jquery/jquery.ui.core.min.js', false, true, false, false, false);
    }
}


/**
 * Stupid but necessary way of adding jQuery Chosen to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that the script is loaded after jQuery
 *
 */
function triggerEschoolScriptjQueryChosen()
{
    if (version_compare(JVERSION, '3', 'ge')) {
        JHtml::_('script', 'jui/chosen.jquery.min.js', false, true, false, false, false);
		JHtml::_('stylesheet', 'jui/chosen.css', false, true);
    }
    else {
        JHtml::_('script', 'com_eschool/chosen/chosen.jquery.min.js', false, true, false, false, false);
        JHtml::_('stylesheet', 'com_eschool/chosen/chosen.css', false, true);
    }
}

/**
 * Stupid but necessary way of adding jQuery Sortable to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that flot is loaded after jQuery
 *
 */
function triggerEschoolScriptjQuerySortable()
{
    $scripts = (array) array_keys(JFactory::getDocument()->_scripts);
    $string  = implode('', $scripts);

    if (stripos($string, 'jquery.ui.sortable') === false) {
        JHtml::_('script', 'com_eschool/jquery/jquery.ui.sortable.min.js', false, true, false, false, false);
    }
}


/**
 * Stupid but necessary way of adding Bootstrap JS to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that Bootstrap JS is not already loaded
 *
 */
function triggerEschoolScriptBootstrap()
{
    $params = JComponentHelper::getParams('com_eschool');

    $load = $params->get('bootstrap_js');

    // Auto-load
    if ($load == '') {
        $scripts = (array) array_keys(JFactory::getDocument()->_scripts);
        $string  = implode('', $scripts);

        if (stripos($string, 'bootstrap') === false) {
            JHtml::_('script', 'com_eschool/bootstrap/bootstrap.min.js', false, true, false, false, false);
        }
    }

    // Force load
    if ($load == '1') {
        JHtml::_('script', 'com_eschool/bootstrap/bootstrap.min.js', false, true, false, false, false);
    }
}


/**
 * Stupid but necessary way of adding jQuery Flot to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that flot is loaded after jQuery
 *
 */
function triggerEschoolScriptFlot()
{
    JHtml::_('script', 'com_eschool/flot/jquery.flot.min.js', false, true, false, false, false);
    JHtml::_('script', 'com_eschool/flot/jquery.flot.pie.min.js', false, true, false, false, false);
    JHtml::_('script', 'com_eschool/flot/jquery.flot.resize.min.js', false, true, false, false, false);
}

/**
 * Stupid but necessary way of adding PF form JS to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that the form JS is loaded after jQuery
 *
 */
function triggerEschoolScriptForm()
{
    JHtml::_('script', 'com_eschool/Eschool/form.js', false, true, false, false, false);
}

/**
 * Stupid but necessary way of adding PF tasks JS to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that the tasks JS is loaded after jQuery
 *
 */
function triggerEschoolScriptTask()
{
    JHtml::_('script', 'com_eschool/Eschool/task.js', false, true, false, false, false);
}

/**
 * Stupid but necessary way of adding PF core JS to the document head.
 * This function is called by the "onCompileHead" system event and makes sure that the core JS is loaded after jQuery
 *
 */
function triggerEschoolScriptCore()
{
    JHtml::_('script', 'com_eschool/eschool/eschool.js', false, true, false, false, false);
}

