<?php
/**
 * @version		$Id: modal.php 21529 2011-06-11 22:17:15Z chdemko $
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');

$function	= JRequest::getCmd('function', 'jSelectArticle');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&view=fieldsattachunidades&layout=modal&tmpl=component&function='.$function);?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">
			<!--<label for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
   -->
		</div>

		<div class="right">
			 <select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
				<option value="-1"><?php echo JText::_('- Choose group -');?></option>
				<?php echo JHtml::_('select.options', fieldsattachHelper::getGroups(), 'value', 'text', $this->state->get('filter.group_id'));?>
                        </select>
                        <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                                    <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
                                    <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
                        </select>
		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
                                    <?php echo JText::_('COM_FIELDSATTACH_FIELDSATTACH_HEADING_ID'); ?>
                                </th>
                                <th width="20">
                                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                                </th>
                                <th>
                                        <?php echo JText::_('COM_FIELDSATTACH_FIELDSATTACH_HEADING_TITLE'); ?>
                                </th>
                                <th width="20%">
                                        <?php echo JText::_('COM_FIELDSATTACH_FIELDSATTACH_HEADING_TYPE'); ?>
                                </th>
                                <th width="10%">
                                        <?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
                                </th>
                                <th width="5%">
                                        <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'state', $listDirn, $listOrder); ?>
                                </th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
                     <tr class="row<?php echo $i % 2; ?>">
                            <td>
                                    <?php echo $item->id; ?>
                            </td>
                            <td>
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td>
                                <a href="#"  onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', '<?php echo $this->escape($item->groupid); ?>', '<?php echo JRequest::getVar("object","");?>', '<?php echo $item->type;?>');">
                                            <?php echo $item->title; ?>
                                    </a>
                            </td>
                            <td>
                                    <?php echo $item->type; ?>
                            </td>
                            <td class="order">
                                    <input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>"  disabled class="text-area-order" />
                            </td>
                            <td class="center">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'fieldsattachunidades.', false, 'cb', false, false); ?>
                            </td>
                    </tr>
 

			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" /> 
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
