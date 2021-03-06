<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$print_url = JRoute::_('index.php?option=com_eschool&view=grades&tmpl=component&layout=print');
$print_opt = 'width=1024,height=600,resizable=yes,scrollbars=yes,toolbar=no,location=no,directories=no,status=no,menubar=no';
?>
<form action="<?php echo JRoute::_('index.php?option=com_eschool&view=grades');?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search"
				value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				title="<?php echo JText::_('COM_ESCHOOL_GRADES_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			<?php if ($this->state->get('filter.regsemester', 0) > 0) :?>
			<a class="btn button" id="print_btn" href="javascript:void(0);" onclick="window.open('<?php echo JRoute::_($print_url);?>', 'print', '<?php echo $print_opt; ?>')">
                 <?php echo JText::_('COM_ESCHOOL_PRINT'); ?>
            </a>
            <?php endif;?>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_regsemester" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ESCHOOL_OPTION_SELECT_SEMESTER');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('regsemester.options'),
					'value', 'text', $this->state->get('filter.regsemester'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
	<?php $fullname = EschoolHelper::getNameTitle($this->items[0]->title).' '.$this->items[0]->first_name.' '.$this->items[0]->last_name;
		$studentCode = $this->items[0]->student_code;
	?> 
	<fieldset>
		<legend><?php echo $this->escape($fullname).' ['.$studentCode.']';?></legend>
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
