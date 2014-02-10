<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
 
JLoader::register('fieldattach',  JPATH_ROOT.DS.'components/com_fieldsattach/helpers/fieldattach.php');

//           
?>
<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_fieldsattach');?>" method="post">

	<fieldset class="word">
		<label for="search-searchword">
			<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
		</label>
		<div class="btn-group pull-left">
			<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
		</div>
		<div class="btn-group pull-left">
			<button name="Search" onclick="this.form.submit()" class="btn hasTooltip" title="<?php echo JText::_('COM_SEARCH_SEARCH');?>"><i class="icon-search"></i></button>
		</div>
		<input type="hidden" name="option" value="com_fieldsattach" />
                <input type="hidden" name="task" value="advancedsearch" />  
                <input type="hidden" name="advancedsearchcategories" value="<?php echo $this->advancedsearchcategories;?>" /> 
                <input type="hidden" name="fields" id="filterfields" value="<?php echo $this->fields;?>" />
                <input type="hidden" name="Itemid" value="<?php echo  JRequest::getVar("Itemid"); ?>" />
	
	</fieldset>
	<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', '<span class="badge badge-info">'. $this->total. '</span>');?></p>
		<?php endif;?>
	</div>

	<!--<div  class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
		<?php endif;?>
	</div>-->
	
        <?php if(!empty( $this->fields)){ ?>
        <fieldset id="filterfieldsattach" class="phrases filterfieldsattach">
                <legend><?php echo JText::_('COM_FIELDSATTACH_FILTER');?></legend>
                <?php 
                $arrayoffields = explode("," , $this->fields);
                $arrayparamrules = explode("," , $this->paramrules);
                 
                $cont=0;
                foreach ($arrayoffields as $fieldsid) :
                    $val = explode("_" , $fieldsid);
                     
                    $info = FieldsattachViewAdvancedSearch::getInfo($val[0]);
                    $type = $info->type;
                    $title = $info->title;
                    $valor = ""; 
                    if(count($val)>1) $valor= $val[1];
                   ?>
                    <div class="field"><p><label for="field_<?php $val[0]?>"><?php echo $title;?></lable></p>
                    <?php  if($type == "select" || $type == "selectmultiple" )
                    {
                        //echo " -- select";
                        //NEW
                        //JPluginHelper::importPlugin('fieldsattachment'); // very important
                        echo FieldsattachViewAdvancedSearch::renderSelect( $val[0], $valor );
                 
                    }else{
			
			if($type == "selecttree"){
				JPluginHelper::importPlugin('fieldsattachment');
				echo plgfieldsattachment_selecttree::renderInput( "", $val[0], $valor ,"");
				 
			}else{
			
                        //BETWEEN ***********************************
                        if(count($arrayparamrules)>$cont){
                        if($arrayparamrules[$cont]=="BETWEEN"){
                            


                            $values = explode("|", $valor);
                            $val1 = $values[0];
                            $val2 ="";
                            if(count($values)>1) $val2 = $values[1];
                            ?>
                            <?php echo JText::_("BETWEEN");?>
                            <input type="hidden" name="field_<?php echo $val[0];?>" id="field_<?php echo $val[0];?>" value="<?php echo $valor;?>"  />
                            <input name="field_<?php echo $val[0];?>_1" id="field_<?php echo $val[0];?>_1" value="<?php echo $val1;?>" onchange="changebetween(this)" />
                            <?php if($type == "date" ){ ?>
                                <img src="<?php echo $this->baseurl;?>/templates/system/images/calendar.png" alt="Calendar" class="calendar" id="field_<?php echo $val[0];?>_1_img">
                            <?php } ?>
                            <?php echo JText::_("AND");?>
                            <input name="field_<?php echo $val[0];?>_2" id="field_<?php echo $val[0];?>_2" value="<?php echo $val2;?>" onchange="changebetween(this)" />
                            <?php if($type == "date" ){ ?>
                                <img src="<?php echo $this->baseurl;?>/templates/system/images/calendar.png" alt="Calendar" class="calendar" id="field_<?php echo $val[0];?>_2_img">
                            <?php } ?> 
                            <?php 
                            //DATE ************************
                            if($type == "date" ){
                            $format ="%Y-%m-%d";
                            
                            $extrainfo = fieldattach::getExtra($val[0]);  
                            if(count($extrainfo)>0) if(!empty($extrainfo[0])) $format = $extrainfo[0]; 
                            
                            $value=$val1;
                            JHTML::_('calendar', $value, 'field_'.$val[0].'_1' , 'field_'.$val[0].'_1', $format,array('class'=>'customfields inputbox ', 'size'=>'25',  'maxlength'=>'19'));
                            $value=$val2; 
                            JHTML::_('calendar', $value, 'field_'.$val[0].'_2' , 'field_'.$val[0].'_2', $format,array('class'=>'customfields inputbox ', 'size'=>'25',  'maxlength'=>'19'));
  
                            ?>
                            <script>
                                window.addEvent('domready', function() {
                                    Calendar.setup({
                                        // Id of the input field
                                        inputField:  "field_<?php echo $val[0];?>_1",
                                        // Format of the input field
                                        ifFormat: <?php echo $format;?>,
                                        // Trigger for the calendar (button ID)
                                        button: "field_<?php echo $val[0];?>_1_img",
                                        // Alignment (defaults to "Bl")
                                        align: "Tl",
                                        singleClick: true,
                                        firstDay: 0
                                    });
                                    Calendar.setup({
                                        // Id of the input field
                                        inputField:  "field_<?php echo $val[0];?>_2",
                                        // Format of the input field
                                        ifFormat: <?php echo $format;?>,
                                        // Trigger for the calendar (button ID)
                                        button: "field_<?php echo $val[0];?>_2_img",
                                        // Alignment (defaults to "Bl")
                                        align: "Tl",
                                        singleClick: true,
                                        firstDay: 0
                                    });
                                });
                            </script>
                            <?php }?> 
                        <?}else{
                        ?>
                            <input name="field_<?php echo $val[0];?>" value="<?php echo $valor;?>" onchange="changefilter1()" />
                            
                    <?php
                        }
                        }else{?>
                             <input name="field_<?php echo $val[0];?>" value="<?php echo $valor;?>" onchange="changefilter1()" />
                           <?php
                        }
			}
                    }
                    $cont++;
                ?></div>
                <?php endforeach; ?>    
        </fieldset>
        <?php } ?>
	<fieldset class="phrases">
		<legend><?php echo JText::_('COM_SEARCH_FOR');?></legend>
                <div class="phrases-box">
                <?php echo $this->lists['searchphrase']; ?>
                </div>
        </fieldset>

	<?php 
        /*if ($this->params->get('search_areas', 1)) : ?>
		<fieldset>
		<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
		<?php foreach ($this->searchareas['search'] as $val => $txt) :
			$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
		?>
		<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
			<label for="area-<?php echo $val;?>">
				<?php echo JText::_($txt); ?>
			</label>
		<?php endforeach; ?>
		</fieldset>
	<?php endif; */?>

<?php if ($this->total > 0) : ?>

	 
<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>

<?php endif; ?>

</form>

