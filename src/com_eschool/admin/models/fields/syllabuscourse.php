<?php
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');


/**
 * Form Field class for selecting a project.
 *
*/
class JFormFieldSyllabuscourse extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'Syllabuscourse';

	protected $syllabus_id;

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The html field markup
	 */
	protected function getInput()
	{
		// Load the modal behavior script
		JHtml::_('behavior.modal', 'a.modal_' . $this->id);

		$hidden = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="" />';
		// Get parent item field values.
		$syllabus_id   = (int) $this->form->getValue('syllabus_id');

		$this->syllabus_id   = $syllabus_id;

		// Add the script to the document head.
		$script = $this->getJavascript();
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Load the current project title a value is set.
		$title = ($this->value ? $this->getCourseTitle() : JText::_('COM_ESCHOOL_SELECT_A_COURSE'));

		if ($this->value == 0) $this->value = '';

		$html = $this->getHTML($title);

		return implode("\n", $html);
	}


	/**
	 * Method to generate the input markup.
	 *
	 * @param     string    $title    The title of the current value
	 *
	 * @return    string              The html field markup
	 */
	protected function getHTML($title)
	{
		if (JFactory::getApplication()->isSite() || version_compare(JVERSION, '3.0.0', 'ge')) {
			return $this->getSiteHTML($title);
		}

		return $this->getAdminHTML($title);
	}


	/**
	 * Method to generate the backend input markup.
	 *
	 * @param     string    $title    The title of the current value
	 *
	 * @return    array     $html     The html field markup
	 */
	protected function getAdminHTML($title)
	{
		$html = array();
		$link = 'index.php?option=com_eschool&amp;view=syllabuscourses'
				. '&amp;layout=modal&amp;tmpl=component&amp;filter_syllabus_id='.$this->syllabus_id
				. '&amp;func=esSelectCourse_' . $this->id;

		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"'      : '';

		// Create a dummy text field with the contact title.
		$html[] = '<div class="fltlft">';
		$html[] = '    <input type="text" id="' . $this->id . '_name" value="' . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . '" disabled="disabled"' . $attr . ' />';
		$html[] = '</div>';

		// Create the project select button.
		if ($this->element['readonly'] != 'true') {
			$html[] = '<div class="button2-left">';
			$html[] = '    <div class="blank">';
			$html[] = '<a class="modal_' . $this->id . '" title="' . JText::_('COM_ESCHOOL_SELECT_COURSE') . '"'
					. ' href="' . $link . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html[] = JText::_('COM_ESCHOOL_SELECT_COURSE') . '</a>';
			$html[] = '    </div>';
			$html[] = '</div>';

		}

		// Create the hidden field, that stores the id.
		$html[] = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" />';

		return $html;
	}


	/**
	 * Method to generate the frontend input markup.
	 *
	 * @param     string    $title    The title of the current value
	 *
	 * @return    array     $html     The html field markup
	 */
	protected function getSiteHTML($title)
	{
		$html = array();
		$isJ3 = version_compare(JVERSION, '3.0.0', 'ge');

		$link = PFticketsHelperRoute::getContactsRoute()
		. '&amp;layout=modal&amp;tmpl=component&amp;exclude_syllabus_id='.$this->syllabus_id
		. '&amp;func=esSelectCourse_' . $this->id;


		// Initialize some field attributes.
		$attr  = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size']  ? ' size="'.(int) $this->element['size'].'"'      : '';

		if ($isJ3) {
			$html[] = '<div class="input-append">';
		}
		// Create a dummy text field with the contact title.
		$html[] = '<input type="text" id="' . $this->id . '_name" value="' . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . '" disabled="disabled"' . $attr . ' />';

		// Create the project select button.
		if ($this->element['readonly'] != 'true') {

			$html[] = '<a class="modal_' . $this->id . ' btn" title="' . JText::_('COM_ESCHOOL_SELECT_COURSE') . '"'
					. ' href="' . JRoute::_($link) . '" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
			$html[] = JText::_('COM_ESCHOOL_SELECT_COURSE') . '</a>';
		}

		if ($isJ3) {
			$html[] = '</div>';
		}

		// Create the hidden field, that stores the id.
		$html[] = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="' . (int) $this->value . '" />';

		return $html;
	}


	/**
	 * Generates the javascript needed for this field
	 *
	 * @param     boolean    $submit    Whether to submit the form or not
	 * @param     string     $view      The name of the view
	 *
	 * @return    array      $script    The generated javascript
	 */
	protected function getJavascript()
	{
		$script   = array();
		$onchange = $this->element['onchange'] ? $this->element['onchange'] : '';

		$script[] = 'function esSelectCourse_' . $this->id . '(id, title, syllabus_id)';
		$script[] = '{';
		$script[] = '    var old_id = document.getElementById("' . $this->id . '_id").value;';
		$script[] = '     if (old_id != id) {';
		$script[] = '         document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '         document.getElementById("' . $this->id . '_name").value = title;';
		$script[] = '         document.getElementById("jform_syllabus_id").value = syllabus_id;';
		$script[] = '         SqueezeBox.close(); ';
		$script[] = '         ' . $onchange;
		$script[] = '     }';
		$script[] = '}';

		return $script;
	}


	/**
	 * Method to get the title of the currently selected province
	 *
	 * @return    string    The project title
	 */
	protected function getCourseTitle()
	{
		$default = JText::_('COM_ESCHOOL_SELECT_A_COURSE');

		if (empty($this->value)) {
			return $default;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.title, a.course_code')
		->from('#__eschool_courses AS a')
		->where('a.id = ' . $db->quote($this->value));

		$db->setQuery((string) $query);
		$row = $db->loadObject();

		if (empty($row)) {
			return $default;
		}

		return $row->title.' ('.$row->course_code.')';
		
	}
}

