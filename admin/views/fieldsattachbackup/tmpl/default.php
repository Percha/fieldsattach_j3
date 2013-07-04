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
<div id="cpanel">
        <div style=" overflow: hidden;">
		<div class="m" style="background-color:#fff; padding:10px;"><?php echo JTEXT::_("INTRO_BACKUP");?></div>
                <div style=" overflow: hidden;">
                <div style="float:left; width:15%;">
                    <h3 style="font-size:18px;">Export</h3>
                        <div class="icon">
                            <div class="icon">
                                <a href="index.php?option=com_fieldsattach&view=fieldsattachbackup&task=export">
                                    <img src="components/com_fieldsattach/images/export.png" alt="Backup"  />
                                    <span><?php echo JText::_( 'Export' );?></span>
                                </a>
                            </div>
                        </div>
                </div>
                <div style="float:left; width:30%;"> 
                    <h3 style="font-size:18px;">Import</h3>
                    <div class="icon">
                            <div class="icon">
                                <a href="#" onclick="document.forms['importfields'].submit();">
                                    <img src="components/com_fieldsattach/images/import.png" alt="Backup"  />
                                    <span><?php echo JText::_( 'Import' );?></span>
                                </a>
                            </div>
                        </div>
                    
                    <form name="importfields" id="importfields" action="index.php?option=com_fieldsattach&view=fieldsattachbackup&task=import" method="post"
enctype="multipart/form-data">
                        <input type="file" name="file" /> 
                    </form>                       
                </div>
        </div>  
        
                
        </div> 
</div>