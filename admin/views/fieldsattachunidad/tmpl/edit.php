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
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal'); 
JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');

$params = $this->form->getFieldsets('params');

$content = "
window.addEvent('domready', function() { 
 ";
$content .= "$$('#jform_required').addEvent('change',function(event) {
     
    var type = $$('#jform_type').get('value');
    var myFieldsaccess=new Array('input', 'select', 'selectmultiple', 'vimeo', 'youtube' , 'date', 'link', 'image', 'file', 'checkbox' ); // condensed array
    
    var success = false;
    var num = myFieldsaccess.length;
    for(var i=0; i<num; i++)
    {
        if(myFieldsaccess[i] == type){
             success = true;
             break;
        }  
    }
    
    if(!success)
    {
        alert('".JText::_("REQUIRED_NOT_PERMITED")."');
        $(this).set('value', '0');
    }
});";

/*$content .= "
        $$('#jform_type').addEvent('change',function(event) {  
                $$('#jform_required').set('value', '0'); 
                 
});";*/

$content .= "});";



$doc = JFactory::getDocument();
$doc->addScriptDeclaration( $content );
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
			<li><a href="#extra" data-toggle="tab"><?php echo JText::_('COM_FIELDSATTACH_OPTIONS');?></a></li>
	 	</ul>
                 
		<div class="tab-content">
                    <div class="tab-pane active" id="details">	 
                        <?php foreach($this->form->getFieldset('details') as $field): ?>
                                  <div class="control-group">
                             <div class="control-label"><?php echo $field->label; ?></div>
                                <div class="controls"> 
                                    <?php  if ($field->name != "jform[type]") { 
                                        echo $field->input; 
                                    }
                                    else{
                                         
                                        echo '<select id="jform_type" name="jform[type]" class="inputbox required">';
                                        echo ' <option value="">Select one please</option>';
                                        JPluginHelper::importPlugin('fieldsattachment'); // very important
                                        //select
                                        $db = &JFactory::getDBO(  );
                                        $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1 ORDER BY a.element';
                                        $db->setQuery( $query );
                                        $results = $db->loadObjectList();
                                        foreach ($results as $obj)
                                        {
                                            $base = JPATH_SITE;
					                        $file = $base.'/plugins/fieldsattachment/'.$obj->element.'/'.$obj->element.'.php';
					                        //echo "<br>".$file;
					                        if( JFile::exists($file)){
					                            //file exist
					                            //$function  = "plgfieldsattachment_".$obj->element."::construct();";
											    //$name  = "plgfieldsattachment_".$obj->element."::getName();";
	                                            //eval('echo '. $function.';');
	                                            //$function  = "plgfieldsattachment_".$obj->element."::getoptionConfig('".$field->value."');";
                                                $function  = "plgfieldsattachment_".$obj->element."::getoptionConfig('".$field->value."','".$obj->element."');";
	                                            eval('echo '. $function.';');
					                        }
                                             
                                        } 
                                        echo '</select>';
                                    }
                                    ?>
                                </div></div>
                        <?php endforeach; ?>
                    </div>
                 
                    

                    <div class="tab-pane"  id="extra">
                            <?php

                            $db = JFactory::getDBO();
                            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
                            $db->setQuery( $query );
                            $results = $db->loadObjectList();

                            
                            foreach ($results as $obj)
                            {
                                 
                                //echo JHtml::_('sliders.start', 'fieldsattach-slider-'.$obj->element);
                                //echo '<div id="fieldsattach-slider-'.$obj->element.'" class="pane-sliders"><div id="percha_'.$obj->element.'-params" class="content">';
                                echo '<div id="fieldsattach-slider-'.$obj->element.'" class="pane-sliders"><div class="panel" >';
                                echo '<div class="pane-slider content pane-down" >';
                                echo  fieldsattachHelper::getForm($obj->element);
                                echo '</div></div></div>';
                                /*$function  = "plgfieldsattachment_".$obj->element."::construct();";
                                eval('echo '. $function.';'); 
                                $function  = "plgfieldsattachment_".$obj->element."::renderHelpConfig();";
                                eval('echo '. $function.';');*/
                            // echo  JHtml::_('sliders.end');
                            } 
                            ?>
		
                    </div>
            </div>
        </div>
	 
		<input type="hidden" name="task" value="fieldsattachunidad.edit" />
		<?php echo JHtml::_('form.token'); ?>
	 
</form>

