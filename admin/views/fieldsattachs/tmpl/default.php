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
<table class="adminform">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
                            <div style=" overflow: hidden;">
			<div style="float:left; width:110px; text-align: center; margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachgroups">
                                        <img src="components/com_fieldsattach/images/groups.png" alt="Groups"  />
                                        <span style="text-transform: uppercase; color:#fff; background-color: #333; padding:2px 5px;"><?php echo JText::_( 'Groups' );?></span>
                                    </a>
                                </div>
                        </div>
                        <div style="float:left; width:110px; text-align: center;margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachunidades">
                                        <img src="components/com_fieldsattach/images/units.png" alt="Fields"  />
                                        <span style="text-transform: uppercase; color:#fff; background-color: #333; padding:2px 5px;"><?php echo JText::_( 'Fields' );?></span>
                                    </a>
                                </div>
                        </div>
                        <div style="float:left; width:110px; text-align: center;margin-right:10px;">
                            <div class="icon">
                                <a href="index.php?option=com_fieldsattach&view=fieldsattachbackup">
                                    <img src="components/com_fieldsattach/images/backup.png" alt="Backup"  />
                                    <span style="text-transform: uppercase; color:#fff; background-color: #333; padding:2px 5px;"><?php echo JText::_( 'Backup' );?></span>
                                </a>
                            </div>
                        </div>
                        <div style="float:left; width:150px; text-align: center;margin-right:10px;">
                                <div class="icon">
                                    <a href="index.php?option=com_fieldsattach&view=fieldsattachdisplay">
                                        <img src="components/com_fieldsattach/images/help.png" alt="Help"  />
                                        <span style="text-transform: uppercase;color:#fff; background-color: #333; padding:2px 5px;"><?php echo JText::_( 'FrontEnd display' );?></span>
                                    </a>
                                </div>
                        </div>
                             
</div>
							
                            
                            <!-- HELP-->
                            <div style="width:85%;  overflow: hidden; border: #ccc 1px solid; padding: 10px; margin:30px 10px;">
                                <div style="float:left; margin-right: 10px;width:80px;"><img src="components/com_fieldsattach/images/easy.jpg" alt="Support" style=""/></div>
                                 <div style="float:left;margin:20px   0px ; width:300px; display: block;  ">
                                     <strong><?php echo JText::_( 'COM_FIELDSATTACH_HELP_TITLE' );?></strong><br />
                                 <?php echo JText::_( 'COM_FIELDSATTACH_HELP_STEP1_TITLE' );?><br />
                                 <?php echo JText::_( 'COM_FIELDSATTACH_HELP_STEP1_DESCRIPTION' );?>
                                 <?php echo JText::_( 'COM_FIELDSATTACH_HELP_STEP2_TITLE' );?><br />
                                 <?php echo JText::_( 'COM_FIELDSATTACH_HELP_STEP2_DESCRIPTION' );?>
                                 </div>

                            </div>
                              
 						<!-- SUPPORT FORUM-->
                            <div style="width:85%;  overflow: hidden; border: #ccc 1px solid; padding: 10px; margin:30px 10px;">
                                 <img src="components/com_fieldsattach/images/gssupport.png" alt="Support" style="float:left; margin-right: 10px;"/>
                                 <div style="margin:20px   0px ;"><strong><?php echo JText::_( 'COM_FIELDSATTACH_DO_YOU_HAVE_A_PROBLEM_OR_SUGGESTION' );?></strong><br />
                                 <?php echo JText::_( 'COM_FIELDSATTACH_DO_YOU_HAVE_A_PROBLEM_OR_SUGGESTION_TXT' );?></div>

                            </div>

			</div>

		</td>

		<td width="45%" valign="top">
			<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
			<div style="float:right;margin:10px;">
				<a href="http://www.fieldsattach.com/" target="_blank"><img src="components/com_fieldsattach/images/logo.png" alt="Percha.com"  /></a></div>
			<h3>Version</h3>
			<p><?php echo $this->version;?></p>
			<div id="checkupdates">
				<form id="checkupdatesForm">
					Checking for updates...
					<input type="hidden" name="host" id="host" value="<?php echo $_SERVER["SERVER_ADDR"];?>" />
					<input type="hidden" version="<?php echo $this->version;?>" name="version" id="version" />
					<?php echo JHtml::_('form.token'); ?>
				</form> 
			</div>
			<p></p>
			<p><a href="http://www.fieldsattach.com/" target="_blank">www.fieldsattach.com</a></p></p>

			<h3>Copyright</h3>
			<p>© 2009 - 2012 Cristian Grañó Reder<br />
			<a href="http://www.percha.com/" target="_blank">www.percha.com</a></p>

			<h3>License</h3>
			<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
			<p>&nbsp;</p> 
			</div>
			
            <!-- REVIEW-->
            <div style="  overflow: hidden; border: #ccc 1px solid; padding: 10px; margin:30px 10px;">
                 <img src="components/com_fieldsattach/images/smile.jpg" alt="" style="float:left; margin-right: 10px;"/>
                 <div style="margin:20px   0px ;"><strong><?php echo JText::_( 'COM_FIELDSATTACH_DO_YOU_LIKE' );?></strong><br />
                 <?php echo JText::_( 'COM_FIELDSATTACH_DO_YOU_LIKE_TXT' );?></div>
               
            </div>
           <!-- PAYPAL-->
            <div style="  overflow: hidden; border: #ccc 1px solid; padding: 10px; margin:30px 10px;">
                 <img src="components/com_fieldsattach/images/paypal.png" alt="" style="float:left; margin-right: 10px;"/>
                 <div style="margin:20px   0px ;"><strong><?php echo JText::_( 'COM_FIELDSATTACH_COFFE' );?></strong><br />
                 <?php echo JText::_( 'COM_FIELDSATTACH_COFFE_TXT' );?></div>

            </div>

		</td>
	</tr>
</table>
