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
class JFormFieldSelectorder extends JFormField
{
	 /**
      * The form field type.
      *
      * @var  string
      * @since	1.6
      */
      protected $type = 'Selectorder'; //the form field type

            /**
      * Method to get content articles
      *
      * @return	array	The field option objects.
      * @since	1.6
      */
	protected function getInput()
	{
         
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = (array) $this->getOptions();

		// Create a read-only list (no name) with a hidden input to store the value.
		if ((string) $this->element['readonly'] == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
		}
		// Create a regular list.
		else
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}

                
                //
                // Get default menu - JMenu object, look at JMenu api docs
                $menu = JFactory::getApplication()->getMenu();

                // Get menu items - array with menu items
                $items = $menu->getMenu();
                //print count($items);
                foreach($items as $item)
                {
                    //echo "aaa: ".$item."<br>";
                }

		return implode($html);
            
  
        }
        /**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
            // Initialize variables.
		$options = array(); 
		foreach ($this->element->children() as $option)
		{

			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}
                //Search options
                
                $fieldsids = JFormFieldSelectorder::getFieldsIds();
                
                //$tmp = JFormFieldSelectorder::getFieldsOpt($fieldsidss);
                
                $Itemid = JRequest::getVar("id",-1);
                if($Itemid>0 && !empty($fieldsid)){
			if(is_numeric($fieldsid)){
				$db = &JFactory::getDBO();
				foreach( $fieldsids as $fieldsid )
				{
	    
					$query = 'SELECT id, title FROM #__fieldsattach WHERE id='.$fieldsid;
					$db->setQuery( $query );
					$obj = $db->loadObject();
	    
					$tmp = JHtml::_('select.option', $obj->id,  $obj->title ." ASC");
					$options[] = $tmp;
					$tmp = JHtml::_('select.option', $obj->id ." DESC",  $obj->title ." DESC");
					$options[] = $tmp;
				}
			}
                }
                 
                
                //echo parse_url($link, PHP_URL_PATH);
               
                

		reset($options);

		return $options;
        }
        
        
        /**
	 * Method to get the fields id.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getFieldsIds()
	{
             //now get to the business of finding the articles
            
            $Itemid = JRequest::getVar("id",-1);

            if($Itemid>0){

            $db = &JFactory::getDBO();
            $query = 'SELECT link FROM #__menu WHERE id='.$Itemid;
            $db->setQuery( $query );
            $link = $db->loadObject();

             
            //Get param link
            $query  = explode('&', $link->link);
            $params = array();

            foreach( $query as $param )
            {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);

            }

            $fieldsparam = $params["fields"][0];


            //get fields ids
            $query  = explode(',', $fieldsparam);
            $ids = array();
            foreach( $query as $obj )
            {

                list($id, $value) = explode('_', $obj);


            // $obj[urldecode($name)][] = urldecode($value);
                $ids[] = $id;

            }
          }else{
             $ids= $Itemid ;
          }
          
          return $ids;
           
 
        } 
} 

