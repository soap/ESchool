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

	public function ajaxadd()
	{
		$app   = JFactory::getApplication();
		$data = array();
		$input = array();
		
		$input['syllabus_id'] = $app->input->getInt('syllabus_id');
		$input['course_id'] = $app->input->getInt('course_id');
		
		$model = $this->getModel();
		if (!$model->save($input)) {
			$data['success'] =  false;
			$this->sendResponse($data);
		}
		
		$data['success'] = true;
	}
	/**
	 * Sends a JSON response to the browser
	 *
	 * @param     string    $data    The data to send
	 *
	 * @return    void
	 **/
	protected function sendResponse($data)
	{
		// Set the MIME type for JSON output.
		JFactory::getDocument()->setMimeEncoding('application/json');
	
		// Change the suggested filename.
		JResponse::setHeader('Content-Disposition', 'attachment;filename="' . $this->view_list . '.json"');
	
		foreach($data AS $key => $value)
		{
			if (is_array($value)) {
				if(count($value) == 0) {
					unset($data[$key]);
				}
			}
		}
	
		// Output the JSON data.
		echo json_encode($data);
	
		jexit();
	}	
}