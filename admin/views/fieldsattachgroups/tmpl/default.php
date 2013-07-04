<?php
/**
 * @version		$Id: default.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');   

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$listOrder	= $this->state->get('list.ordering');
$listOrder	= "a.ordering";
$saveOrder	= $listOrder == 'a.ordering'; 
$listDirn	= $this->escape("asc");

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_fieldsattach&task=fieldsattachgroups.saveOrderAjax&tmpl=component';
	
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>


<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&view=fieldsattachgroups'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-select fltrt">
                    <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content'), 'value', 'text', $this->state->get('filter.category_id'));?>
		   </select>
                    <select name="filter_for" class="inputbox" onchange="this.form.submit()">
				<option value="-1"><?php echo JText::_('JOPTION_SELECT_FOR');?></option>
                                <option value="0" <?php if($this->state->get('filter.for') ==0) echo "selected"?>><?php echo JText::_('JOPTION_SELECT_FOR_ARTICLES');?></option>
                                <option value="1" <?php if($this->state->get('filter.for') ==1) echo "selected"?>><?php echo JText::_('JOPTION_SELECT_FOR_CATEGORY');?></option>

 
		    </select>
                    <select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
		    </select>
                    <select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value="-1"><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                                <option value="1" <?php if($this->state->get('filter.published') ==1) echo "selected"?>><?php echo JText::_('JPUBLISHED');?></option>
                                <option value="0" <?php if($this->state->get('filter.published') ==0) echo "selected"?>><?php echo JText::_('JUNPUBLISHED');?></option>
        	   </select>
		    
		    <div class="btn-group pull-right hidden-phone">
				<!--<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>-->
				<input type="hidden" value="asc" name="directionTable" id="directionTable"  />
			</div>
			<div class="btn-group pull-right">
				<!--<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>-->
				<input type="hidden" value="a.ordering" name="sortTable" id="sortTable"  />
			</div>
                     
		</div> 
	</fieldset>
    <table class="table table-striped" id="articleList">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
