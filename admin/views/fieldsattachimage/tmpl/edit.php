<?php
/**
 * @version		$Id: edit.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
$directory = "administrator/";
if ( JFactory::getApplication()->isAdmin()) {
    $directory = "";
} 
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//$params = $this->form->getFieldsets('params');

$sitepath = JPATH_SITE ; 
 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');



$session = JFactory::getSession();
$articleid =  $session->get('articleid'); 
$catid =  $session->get('catid');
$direct =  JRequest::getVar('direct',false);
//echo "session :: ".$session->get('catid');
//echo "articleid:: ".$session->get('articleid');

$fieldsattachid_tmp =  JRequest::getVar('fieldsattachid', '');
$fieldsattachid = $session->get('fieldsattachid');
if(!empty($fieldsattachid_tmp)) $fieldsattachid= $fieldsattachid_tmp;
 
$extrainfo = fieldattach::getExtra($fieldsattachid);
$galleryimage2="0"; 
$galleryimage3="0"; 
$gallerydescription="0";

if((count($extrainfo) >= 1)&&(!empty($extrainfo[0]))) $galleryimage2= $extrainfo[0];
if((count($extrainfo) >= 2)&&(!empty($extrainfo[1]))) $galleryimage3= $extrainfo[1];
if((count($extrainfo) >= 3)&&(!empty($extrainfo[2]))) $gallerydescription= $extrainfo[2];


 
//defino una sesion y guardo datos
//session_start(); 
setcookie('loginin',"true" , time() + 3600,'/');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) { 
                Joomla.submitform(task, document.getElementById('fieldsattach-form'));
	}
</script>  
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&task=save&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="fieldsattach-form" class="form-validate">
        
		<div class="btn-toolbar" id="toolbar">
		    <div class="btn-group" id="toolbar-apply">
			<button href="#" onclick="Joomla.submitbutton('fieldsattachimage.apply')" class="btn btn-small btn-success">
			<i class="icon-apply icon-white">
			</i>
			Save
			</button>
		    </div>    
		</div>     
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_FIELDSATTACH_FIELDSATTACH_DETAILS' ); ?></legend>

		<input name="jform[articleid]" id="jform_articleid" value="<?php echo $articleid;?>" type="hidden" />
		<input name="jform[catid]" id="jform_catid" value="<?php echo $catid;?>" type="hidden" />
		
		<input name="jform[fieldsattachid]" id="jform_fieldsattachid" value="<?php echo $fieldsattachid;?>" type="hidden" />


		<p>
		    <?php echo $this->form->getLabel('title'); ?>
		    <?php echo $this->form->getInput('title'); ?>
		</p>
		<p>
		    <?php echo $this->form->getLabel('image1'); ?>
		    <?php echo $this->form->getInput('image1'); ?>
		</p>
		
		<?php if($galleryimage2==1){?>
		 <p>
		     <?php echo $this->form->getLabel('image2'); ?>
		     <?php echo $this->form->getInput('image2'); ?>
		  
		 </p>
		<?php } ?>
		<?php if($galleryimage3==1){?>
		<p><?php echo $this->form->getLabel('image3'); ?>
		<?php echo $this->form->getInput('image3'); ?> 
		</p>
		 <?php } ?>
		       

		<p><?php echo $this->form->getLabel('published'); ?>
		<?php echo $this->form->getInput('published'); ?></p>

		<!--<p><?php //echo $this->form->getLabel('ordering'); ?>
		<?php //echo $this->form->getInput('ordering'); ?></p>-->
		
		<?php if($gallerydescription==1){?>
		<p><?php echo $this->form->getLabel('description'); ?>
		<div style="width:500px;" ><?php echo $this->form->getInput('description'); ?></div></p>
		<?php } ?>

		<p><?php echo $this->form->getLabel('id'); ?>
		<?php echo $this->form->getInput('id'); ?></p>
		 
			
			
	</fieldset>
	
	<div> 
		<?php
		$fieldsattachid = JRequest::getVar("fieldsattachid",0);
		if($fieldsattachid==0) $fieldsattachid = $session->get('fieldsattachid');
		?>
		<input type="hidden" name="fieldsattachid" value="<?php echo $fieldsattachid; ?>" />
		<input type="hidden" name="fieldsid" value="<?php echo JRequest::getVar("fieldsid",0); ?>" />
		<input type="hidden" name="task" value="fieldsattachunidad.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
            
</form>
