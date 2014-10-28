<?php
defined('_JEXEC') or die;

class JHtmlSyllabus
{
	protected static $items;

	public function options()
	{
		if (!isset(self::$items))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.id, a.title');
			$query->from('#__eschool_syllabuses AS a');

			$db->setQuery($query);
			$items = $db->loadObjectList();

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