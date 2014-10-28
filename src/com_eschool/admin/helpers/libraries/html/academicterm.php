<?php
defined('_JEXEC') or die;

class JHtmlAcademicterm
{
	protected static $items;

	public function options()
	{
		if (!isset(self::$items))
		{
			$items = array();
			$items[0] = new StdClass();
			$items[0]->id = 1;
			$items[0]->title = JText::_('COM_ESCHOOL_OPTION_ACADEMIC_TERM_FIRST');
			
			$items[1]->id = 2;
			$items[1]->title = JText::_('COM_ESCHOOL_OPTION_ACADEMIC_TERM_SECOND');
			
			$items[2]->id = 3;
			$items[2]->title =  JText::_('COM_ESCHOOL_OPTION_ACADEMIC_TERM_THIRD');
			
			// Assemble the list options.
			self::$items = array();

			foreach ($items as &$item)
			{
				self::$items[] = JHtml::_('select.option', $item->id, $item->title);
			}
		}

		return self::$items;
	}
}