<?php
defined('_JEXEC') or die;

class JHtmlRegistration
{
	protected static $items;
	
	public function options()
	{
		if (!isset(self::$items))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.id AS id');
			$query->from('#__eschool_registrations AS a');
			
			$query->select('CONCAT(a.book_number,LPAD(a.doc_number,4,"0"),"-",s.first_name," ",s.last_name," (", ")") as title');

			$query->join('LEFT','#__eschool_students AS s ON s.id=a.student_id');
			
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