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
// No direct access
defined('_JEXEC') or die('Restricted access');
$dir = dirname(__FILE__);
JLoader::register('fieldsattachHelper',   $dir.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$params = $this->form->getFieldsets('params');

$articlesid ="";
if ($this->item->articlesid)
$articlesid = explode(",",$this->item->articlesid);
 

$str ='
    //FUNCTION AD LI =========================================
    function init_obj(){
    ';
if(!empty($articlesid))
{
    foreach($articlesid as $articleid)
    {
        //$str .='alert("'.getTitle($articleid).'");';
        $str .='var title = "'.fieldsattachHelper::getTitle($articleid).'" ;';
        if(!empty($articleid)) $str .= 'obj.AddId(  '.$articleid.', title);';
    }
}

$str .='
     //alert("init '.$articlesid.'");
     var myArray = String(document.id("jform_articlesid").value).split(\',\');
}';

$document = JFactory::getDocument();  
$document->addScriptDeclaration($str);


?> 



<script type="text/javascript">
	Joomla.submitbutton = function(task) { 
                Joomla.submitform(task, document.getElementById('fieldsattach-form'));
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_fieldsattach&task=save&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="fieldsattach-form" class="form-validate">
<div class="row-fluid"> 
            <div class="span10 form-horizontal">
                 
                <ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_FIELDSATTACH_FIELDSATTACH_DETAILS') ;?></a></li>
			<li><a href="#extra" data-toggle="tab"><?php echo JText::_('COM_fieldsattach_fieldsattach_CATEGORY_LINKS');?></a></li>
                        <li><a href="#article" data-toggle="tab"><?php echo JText::_('COM_FIELDSATTACH_FIELDSATTACH_ARTICLES_LINKS');?></a></li>
	 	
                </ul>
                <div class="tab-content">
                    <!-- DETAILS -->
                    <div class="tab-pane active" id="details">	
                        <?php foreach($this->form->getFieldset('details') as $field): ?>
                            <?php if($field->id != "jform_position"){?>
                             <div class="control-group">
                             <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls"> 
                            <?php echo $field->input;   ?></div></div>
                        <?php } ?>
<?php endforeach; ?> 
                    </div>
                    <!-- END DETAILS -->
                    <!-- EXTRA -->
                    <div class="tab-pane" id="extra">	
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getField('catid')->label; ?></div>
                                <div class="controls">  <?php echo $this->form->getField('catid')->input ;    ?>
                                </div>
                            
                        </div>
                        <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getField('recursive')->label; ?></div>
                                <div class="controls">  <?php echo $this->form->getField('recursive')->input ;    ?>
                                </div>
                            
                        </div>
                        <div class="control-group"> <?php echo   JText::_('GROUP_SELECT_FOR_DESCRIPTION')  ;    ?></div>
                         <div class="control-group">
                            <div class="control-label"><?php echo $this->form->getField('group_for')->label; ?></div>
                                <div class="controls">  <?php echo $this->form->getField('group_for')->input ;    ?>
                                </div>
                            
                        </div>
                          
                    </div>
                    <!-- END EXTRA -->
                    <!-- MORE -->
                    <div class="tab-pane" id="article">
                          <?php echo $this->form->getField('selectarticle')->input ;    ?>
                                            <?php echo $this->form->getField('articlesid')->input ;    ?>
                                            <div style="width:100%; overflow: hidden; margin: 30px 0;">
                                                <ul id="articleslist" style="list-style: none; padding: 0; margin: 0;">
                                                    
                                                </ul>
                                            </div>
                    </div>
                    <!-- END MORE -->
                </div>
            </div>
</div>
    <input type="hidden" name="task" value="fieldsattachunidad.edit" />
		<?php echo JHtml::_('form.token'); ?>

     
</form>

