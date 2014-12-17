<?php
defined('_JEXEC') or die;

class JHtmlSyllabuscourse
{
	protected static $items;
	
	public function options()
	{
		if (!isset(self::$items))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.id AS id');
			$query->from('#__eschool_syllabus_courses AS a');
			
			$query->select('c.title AS title');
			$query->join('LEFT','#__eschool_courses AS c ON c.id=a.course_id');
			
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