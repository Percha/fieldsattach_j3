<?php
/*------------------------------------------------------------------------
# mod_insertfieldsattach
# ------------------------------------------------------------------------
# author    Cristian Grañó (percha.com)
# copyright Copyright (C) 2010 percha.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.percha.com
# Technical Support:  Forum - http://www.percha.com/
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield

 

/**
 * Supports an HTML select list of articles
 * @since  1.6
 */
class JFormFieldFieldsextra extends JFormField
{
	 /**
      * The form field type.
      *
      * @var  string
      * @since	1.6
      */
      protected $type = 'Fieldsextra'; //the form field type

            /**
      * Method to get content articles
      *
      * @return	array	The field option objects.
      * @since	1.6
      */
	protected function getInput()
	{
          // Initialize variables.
          $session = JFactory::getSession();
          $options = array();

          $attr = '';

          // Initialize some field attributes.
          $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

          // To avoid user's confusion, readonly="true" should imply disabled="true".
          if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
           $attr .= ' disabled="disabled"';
          }

          $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
          $attr .= $this->multiple ? ' multiple="multiple"' : '';

          // Initialize JavaScript field attributes.
          $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';


          //now get to the business of finding the articles

          $db = &JFactory::getDBO();
          $query = 'SELECT * FROM #__fieldsattach_groups WHERE published=1 ORDER BY title';
	  
          $db->setQuery( $query );
          $groups = $db->loadObjectList();

          $fields=array();

          // set up first element of the array as all articles
 	  $fields[0] = new stdClass();
          $fields[0]->id = '';
          $fields[0]->title = JText::_("ALLARTICLES");
          
            /*if((int)$this->value>0)
            {
                    $query = 'SELECT title FROM #__content WHERE id='.$this->value;
                     $db->setQuery( $query );
            }*/
  
            //loop through categories
            foreach ($groups as $group) {
                 $optgroup = JHTML::_('select.optgroup',$group->title,'id','title'); 
		  
		 
                 $query = 'SELECT id,title FROM #__fieldsattach WHERE groupid='.$group->id;
                 $db->setQuery( $query );
                 $results = $db->loadObjectList();
                 if(count($results)>0)
                 {
                        array_push($fields,$optgroup);
                        foreach ($results as $result) {
                             array_push($fields,$result);
                        }
                }
            }

            
 
            if ($this->value) {
		    $valortmp = explode("_",$this->value);
                    $query = 'SELECT id,title FROM #__fieldsattach WHERE id='.$valortmp[0];
                    $db->setQuery( $query );
                    $field = $db->loadObject();
            }else {
                    $field->title = JText::_('COM_CONTENT_SELECT_AN_ARTICLE');
            } 
          $link	= 'index.php?option=com_fieldsattach&amp;view=fieldsattachunidades&amp;layout=modal&amp;tmpl=component&amp;function=jSelectFields&object='.$this->id;
          // Output
           

            // Build the script.
            $script = array();
            $script[] = '	function jSelectFields(id, title, catid, object,type) {';
            //$script[] = '		document.id("jform_request_fields").value += id + ",";';
            //$script[] = '		document.id("fieldsid_name").value = title;';
            //$script[] = ' alert(type);';
            $script[] = ' if( (type=="input") || (type=="date") ||(type=="textarea") || (type=="select") || (type=="selectmultiple")  || (type=="tags") || (type=="selecttree") ){';
            $script[] = '		SqueezeBox.close(); obj.AddId(id, title, object,"");';
            $script[] = ' }else{ alert("'.JText::_("TYPE_NOT_VALID").'");}';
            $script[] = '	}';
            
            $script[] = '	';
            
            
            

            $fieldsid = explode(",", $this->value);  

            $str ='
                //FUNCTION AD LI =========================================
                function init_obj(){
                ';
            if($fieldsid)
            {
                foreach($fieldsid as $fieldid)
                {
                    $tmp = explode("_", $fieldid); 
                    //echo $tmp;
                    $text_filtre = "";
                    if(count($tmp)>1) {
                        for($cont=1; $cont<count($tmp); $cont++)
                        {
                            $text_filtre .= $tmp[$cont];
                            if($cont<count($tmp)-1) $text_filtre .= "_"; 
                        }

                    }


                    //$str .='alert("'.$fieldid.'--'.fieldsattachHelper::getFieldsTitle('$fieldid').'");';
                    $str .='var title = "'.fieldsattachHelper::getFieldsTitle($tmp[0]).'" ;';
                    if(!empty($fieldid)) $str .= 'obj.AddId(  '.$tmp[0].', title, "'.$this->id.'", "'.$text_filtre.'");';
                    
                    
                }
            }

            $str .='
            }';

            $document = JFactory::getDocument();  
            $document->addScriptDeclaration($str);

             
            $base =JURI::base();
            $base = str_replace("administrator/", "",$base);
          $js =  $base."components".DS."com_fieldsattach".DS."views".DS."advancedsearch".DS."fields".DS."fieldsextra.js";
	  // Add the script to the document head.
          JFactory::getDocument()->addScript($js);
	  JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
          //$fieldName	= $control_name.'['.$name.']'; 
          JHTML::_('behavior.modal', 'a.modal');
          $html = "\n".'<div style="display:block; width:100%; margin:30px 0 0 0;padding-top:20px; border-top:#ccc 2px solid;">'; 
          $html .= '<div class="button2-left"><div class="blank"><a class="modal btn btn-primary" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
          //$html .= "\n".'<input type="text" id="jform[request][fields]" name="jform_request_fields" value="'.$this->value.'" />'   ;
          
          $html .= "\n".'<input type="hidden" id="'.$this->id.'" name="'.$this->name.'" value="'.$this->value.'" />';
          $html .= "\n".'<input type="hidden" id="jform_request_fields_tmp" name="jform_request_fields" value="'.$this->value.'" />';

          $html .= '<div style="width:100%; margin:20px 0 ; padding:20px 0;  ">
		    <ul id="fieldslist" style="list-style:none; background-color:#eee; padding:10px;">
			
		    </ul>
		</div></div>';
//jform[request][advancedsearchcategories][]

        return $html;
        //return JHTML::_('select.genericlist',  $articles, $this->name, trim($attr), 'id', 'title', $this->value );
  
  }
} 
