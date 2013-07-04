<?php
/**
 * @version		$Id: default_body.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$user		= JFactory::getUser();
$userId		= $user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listOrder	="a.ordering";
/*$saveOrder	= $listOrder=='ordering';*/
$saveOrder	= $listOrder=='a.ordering';
?>
<?php foreach($this->items as $i => $item):
$item->max_ordering = 0; //??
	$ordering   = ($listOrder == 'a.ordering'); ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="order nowrap center hidden-phone"> 
			<?php
			$disableClassName = '';
			$disabledLabel	  = '';

			if (!$saveOrder) :
				$disabledLabel    = JText::_('JORDERINGDISABLED');
				$disableClassName = 'inactive tip-top';
			endif; ?>
			<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
				<i class="icon-menu"></i>
			</span>
			<input type="text"   name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
		 
		</td>
		<td class="center">
                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'fieldsattachunidad.', true, 'cb', false, false); ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_fieldsattach&task=fieldsattachunidad.edit&id=' . $item->id); ?>">
				<?php echo $item->title; ?>
			</a>
		</td>
                <td>
			<?php echo $item->type; ?>
		</td>
                 
                
		<td>
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>

