<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Eschool view.
 *
 * @package     ESchool
 * @subpackage  com_eschool
 * @since       1.0
 */
class EschoolViewScoringplans extends JViewLegacy
{
	/**
	 * @var    array  The array of records to display in the list.
	 * @since  1.0
	 */
	protected $items;

	/**
	 * @var    JPagination  The pagination object for the list.
	 * @since  1.0
	 */
	protected $pagination;

	/**
	 * @var    JObject	The model state.
	 * @since  1.0
	 */
	protected $state;

	/**
	 * Prepare and display the Scoringplans view.
	 *
	 * @return  void
	 * @since   1.0
	 */
	public function display($tp = NULL)
	{
		// Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Add the toolbar if it is not in modal
		if ($this->getLayout() !== 'modal') $this->addToolbar();
		
		// Display the view layout.
		parent::display();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @since   1.0
	 */
	protected function addToolbar()
	{
		// Initialise variables.
		$state	= $this->get('State');
		$canDo	= EschoolHelper::getActions();

		JToolBarHelper::title(JText::_('COM_ESCHOOL_SCORINGPLANS_TITLE'));

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('scoringplan.add', 'JTOOLBAR_NEW');
		}

		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('scoringplan.edit', 'JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::publishList('scoringplans.publish', 'JTOOLBAR_PUBLISH');
			JToolBarHelper::unpublishList('scoringplans.unpublish', 'JTOOLBAR_UNPUBLISH');
			JToolBarHelper::archiveList('scoringplans.archive','JTOOLBAR_ARCHIVE');
		}

		if ($state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'scoringplans.delete','JTOOLBAR_EMPTY_TRASH');
		} 
		else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('scoringplans.trash','JTOOLBAR_TRASH');
		}

	}
}