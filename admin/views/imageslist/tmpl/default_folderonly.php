<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
 

?>
<div class="item" >
      <!--<div clasS="select"><a  href="javascript:ImageManager.populateFields('<?php echo $this->_tmp_folder->path_relative;?>')" title="<?php echo $this->_tmp_folder->name; ?>">
					<?php  echo JText::_("SELECT_FOLDER"); ?> 
     </a>
    </div>-->
    <a href="index.php?option=com_fieldsattach&amp;view=imagesList&amp;only=folder&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_relative; ?>&amp;asset=<?php echo JRequest::getCmd('asset');?>&amp;author=<?php echo JRequest::getCmd('author');?>" onclick="ImageManager.populateFields('<?php echo $this->_tmp_folder->path_relative;?>');">
		<?php echo JHtml::_('image', 'media/folder.gif', $this->_tmp_folder->name, array('height' => 80, 'width' => 80), true); ?>
		<span><?php echo $this->_tmp_folder->name; ?></span>
    </a> 
</div>
