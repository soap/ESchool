<?php
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	// Attach a behaviour to the submit button to check validation.
	Joomla.submitbutton = function(task)
	{
		var form = document.id('exam-form');
		if (task == 'exam.cancel' || document.formvalidator.isValid(form)) {
			Joomla.submitform(task, form);
		}
		else {
			<?php JText::script('COM_ESCHOOL_ERROR_N_INVALID_FIELDS'); ?>
			// Count the fields that are invalid.
			var elements = form.getElements('fieldset').concat(Array.from(form.elements));
			var invalid = 0;

			for (var i = 0; i < elements.length; i++) {
				if (document.formvalidator.validate(elements[i]) == false) {
					valid = false;
					invalid++;
				}
			}

			alert(Joomla.JText._('COM_ESCHOOL_ERROR_N_INVALID_FIELDS').replace('%d', invalid));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_eschool&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="exam-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('scoring_plan_id'); ?>
					<?php echo $this->form->getInput('scoring_plan_id'); ?>
				</li>
				
				<li>
					<?php echo $this->form->getLabel('full_score'); ?>
					<?php echo $this->form->getInput('full_score'); ?>
				</li>
				
				<li>
					<?php echo $this->form->getLabel('weight'); ?>
					<?php echo $this->form->getInput('weight'); ?>
				</li>
				
				<li>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('ordering'); ?>
					<?php echo $this->form->getInput('ordering'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('access'); ?>
					<?php echo $this->form->getInput('access'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('language'); ?>
					<?php echo $this->form->getInput('language'); ?>
				</li>

				<li>
					<?php echo $this->form->getLabel('note'); ?>
					<?php echo $this->form->getInput('note'); ?>
				</li>
			</ul>

			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>

		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start','exam-sliders-'.$this->item->id, array('useCookie' => 1)); ?>

		<?php echo $this->loadTemplate('params'); ?>

		<?php echo $this->loadTemplate('metadata'); ?>
		<?php echo JHtml::_('sliders.end'); ?>

	</div>
	<div class="clr"></div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>