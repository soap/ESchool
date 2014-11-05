<?php
defined('_JEXEC') or die;

JHtml::_('script.jQuery');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$function  = JFactory::getApplication()->input->getCmd('func', 'addCourse');
$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_eschool&tmpl=component&layout=modal&&view=syllabuscourses');?>" method="post" name="adminForm">
		<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search"
				value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
				title="<?php echo JText::_('COM_ESCHOOL_SYLLABUSCOURSES_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit" class="btn">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'),
					'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

			<select name="filter_syllabus_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ESCHOOL_OPTION_SELECT_SYLLABUS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('syllabus.options'),
					'value', 'text', $this->state->get('filter.syllabus_id'));?>
			</select>
			
			<select name="filter_class_level_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ESCHOOL_OPTION_SELECT_CLASS_LEVEL');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('classlevel.options'),
					'value', 'text', $this->state->get('filter.class_level_id'));?>
			</select>
			
			<select name="filter_academic_term" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_ESCHOOL_OPTION_SELECT_ACADEMIC_TERM');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('academicterm.options'),
					'value', 'text', $this->state->get('filter.academic_term'));?>
			</select>

			<select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'),
					'value', 'text', $this->state->get('filter.access'));?>
			</select>

			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true),
					'value', 'text', $this->state->get('filter.language'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist" id="syllabuscoursesList">
		<thead>
			<tr>
				<th>
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_CREDIT', 'a.credit', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_SYLLABUS', 'a.syllabus_id', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_CLASS_LEVEL', 'a.class_level_id', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_ESCHOOL_ACADEMIC_TERM', 'a.academic_term', $listDirn, $listOrder); ?>	
				</th>

				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access_level', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
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
			$canCreate	= $user->authorise('core.create',		'com_eschool.category.'.$item->category_id);
			$canEdit	= $user->authorise('core.edit',			'com_eschool.syllabuscourse.'.$item->id);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state',	'com_eschool.syllabuscourse.'.$item->id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
                <td>
                    <a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>');">
                        <?php echo $this->escape($item->course_title); ?></a>
                </td>
				<td class="center">
					<?php echo $this->escape($item->credit)?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->syllabus_title); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->classlevel_title)?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->academic_term)?>
				</td>
				<td class="order">
					<?php if ($canChange) : ?>
						<span><?php echo $this->pagination->orderUpIcon($i,
							($item->syllabus_id == @$this->items[$i-1]->syllabus_id),
							'syllabuscourses.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i,
							$this->pagination->total,
							($item->syllabus_id == @$this->items[$i+1]->syllabus_id),
							'syllabuscourses.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo (int) $item->ordering; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->access_level); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->author_name); ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('date',$item->created, 'Y-m-d'); ?>
				</td>
				<td class="center">
					<?php if ($item->language == '*'): ?>
						<?php echo JText::_('JALL'); ?>
					<?php else: ?>
						<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
					<?php endif; ?>
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
