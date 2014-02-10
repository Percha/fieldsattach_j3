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
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
 
		<div class="row"><?php echo JTEXT::_("INTRO_BACKUP");?></div>
                
                <div class="row">
			<div class="span10">
				<h3>Export</h3>
				<div class="icon">
				    <div class="icon">
					<img src="components/com_fieldsattach/images/export.png" alt="Backup"  />
					<a href="index.php?option=com_fieldsattach&view=fieldsattachbackup&task=export" class="btn btn-success">
					    
					    <span><?php echo JText::_( 'Export' );?></span>
					</a>
				    </div>
				</div>
			</div>
                </div>
                  <div class="row">
			<div class="span10">
			<h3>Import</h3>
			<div class="icon">
				<div class="icon">
				    <img src="components/com_fieldsattach/images/import.png" alt="Backup"  />
				    <a href="#" onclick="document.forms['importfields'].submit();" class="btn btn-success">
				     
					<span><?php echo JText::_( 'Import' );?></span>
				    </a>
				</div>
			    </div>
			 </div>
                    <form name="importfields" id="importfields" action="index.php?option=com_fieldsattach&view=fieldsattachbackup&task=import" method="post"
enctype="multipart/form-data">
                        <input type="file" name="file" /> 
                    </form>                       
                </div>
        
         