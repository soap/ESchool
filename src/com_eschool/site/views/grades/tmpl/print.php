<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_eschool&view=grades&layout=print&tmpl=component');?>" method="post" name="adminForm">
	<?php $fullname = EschoolHelper::getNameTitle($this->items[0]->title).' '.$this->items[0]->first_name.' '.$this->items[0]->last_name;
		$studentCode = $this->items[0]->student_code;
	?> 
	<fieldset>
		<legend class="center"><?php echo $this->escape($fullname).' ['.$studentCode.']';?></legend>
	</fieldset>

	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_HEADING_SEMESTER', 'semester_title', $listDirn, $listOrder); ?>	
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_HEADING_COURSE_CODE', 'course_code', $listDirn, $listOrder); ?>		
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_HEADING_COURSE', 'course_title', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_HEADING_COURSE_TYPE', 'c.course_type', $listDirn, $listOrder);?>	
				</th>
				<th width="5%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_SCORING_PROGRESS', 'a.scoring_progress', $listDirn, $listOrder); ?>					
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_SCORING_PERCENT', 'a.scoring_percent', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_SCORING_GRADE', 'g.pointing', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="center">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$item->max_ordering = 0; //??
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_eschool');
			$canEdit	= $user->authorise('core.edit',			'com_eschool.grade.'.$item->id);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'com_eschool.grade.'.$item->id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo $this->escape($item->semester_title)?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->course_code)?>
				</td>
				<td class="center">
					<?php echo $item->course_title?>
				</td>
				<td class="center">
					<?php echo EschoolHelper::getCourseTypeTitle($item->course_type)?>
				</td>
				<td class="center">
					<?php echo $item->scoring_progress?>
				</td>
				<td class="center">
					<?php echo $item->scoring_percent?>
				</td>
				<td class="center">
					<?php echo $item->scoring_grade?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('date',$item->created, 'Y-m-d'); ?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
