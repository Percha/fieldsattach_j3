<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$only = JRequest::getVar("only", "");    
?>
<?php 
/*
CHANGE FOR CJamesRun ***
if (count($this->images) > 0 || count($this->folders) > 0) { 
*/
?>
<style>
    .items{ position:relative; float: left;text-align: center;  border:1px #ccc solid; width:110px; height: 110px; margin-right: 5px; margin-bottom: 5px; }
    .items a{ widows: 80px;  } 
    .items   a span{width:80px; text-align: center; }
    .items .select {position: absotule; top:100px; left:0; text-align: center; width: 110px; height: 10px; padding: 0; margin: 0;}
</style>
<?php if (count($this->images) > 0 || count($this->folders) > 0 || count($this->docs) > 0) { ?>
<div class="manager">
        <?php if($only =="folder") {?>
                    <?php for ($i=0, $n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folderonly');
		endfor; ?>
        <?php }else{ ?>

		<?php for ($i=0, $n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0, $n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('image');
		endfor; ?>
                <?php for ($i=0, $n=count($this->docs); $i<$n; $i++) :
			$this->setDocument($i);
			echo $this->loadTemplate('doc');
                        //echo '<br>'.$this->docs[i].title;
		endfor; ?>
     <?php } ?>

</div>
<?php } else { ?>
	<div id="media-nofolder">
		<p><?php echo JText::_('COM_MEDIA_NO_FOLDER_FOUND'); ?></p>
	</div>
<?php } ?>
