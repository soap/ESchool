<?php
defined('_JEXEC') or die;

class JHtmlRegsemester
{
	protected static $items;

	public function options()
	{
		if (!isset(self::$items))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('DISTINCT s.id, s.title');
			
			$query->from('#__eschool_registrations AS a');
			$query->join('LEFT','#__eschool_semesters AS s ON s.id=a.semester_id');

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