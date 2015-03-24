<?php
/**
 * @version		$Id: fieldsattachement.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

 // require helper file
global $sitepath;
$sitepath = JPATH_ROOT ;

JLoader::register('fieldattach',  $sitepath.'/components/com_fieldsattach/helpers/fieldattach.php'); 
 
include_once $sitepath.'/administrator/components/com_fieldsattach/helpers/extrafield.php';

class plgfieldsattachment_input extends extrafield
{
    protected $name;
   /**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
  function plgfieldsattachment_input( $subject, $config)
	{
		parent::__construct($subject, $config); 
  }

	static public function construct1( )
	{
		 parent::getLanguage(plgfieldsattachment_input::getName());   
	}

  static public function getName()
  {  

      return "input";
         // return  $this->name;
  }
	  
         
        function renderHelpConfig(  )
        { 
            $return = "" ;
            $form = $this->form->getFieldset("percha_input");
            $return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_INPUT_OPTIONS"), "percha_".$this->params->get( 'name', "" ).'-params');
            $return .=   '<fieldset class="panelform" >
			<ul class="adminformlist" style="overflow:hidden;">';
           // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
             $return .='</ul> ';
            if(count($form)>0){
            $return .=  '<div><input type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_input()" /></div>';
            }
            $return .=  ' </fieldset>';
            
            return  $return;
        }



        static function renderInput($articleid, $fieldsid, $value , $extras = null)
        {
            
            $required="";
            
            global $sitepath; 
            JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
            
            $boolrequired = fieldattach::isRequired($fieldsid);
            
            
            
            if($boolrequired) $required="required";
            
            $maxlenght="";
            $size=30;
            $defaultvalue="";
            
             //Add CSS ***********************
            $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/input/input.css" type="text/css" />'; 
            $app = JFactory::getApplication();
            $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
            $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/input.css";
            $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/input.css"; 
            if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

            
            
            if(!empty($extras))
            {
                $tmp = $extras;
                $lineas = explode(chr(13),  $tmp); 
               
                foreach ($lineas as $linea)
                {
                    $tmp = explode('|',  $linea);
                    if(!empty( $tmp[0])) $size = $tmp[0];
                    if(count($tmp)>=1) if(!empty( $tmp[1])) $maxlenght = $tmp[1];
                    if(count($tmp)>=2) if(!empty( $tmp[2])) $defaultvalue = $tmp[2];
                     
                    
                }
            }
            
            $value = str_replace ('"', '&quot;', $value); 

            if(empty($value)) $value = $defaultvalue;
            
            $str .= '<div class="file"><input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="text"  value="'.$value.'" class="customfields '.$required.'" size="'.$size.'" maxlength="'.$maxlenght.'" /></div> ';
               
            
            eval('$this_string = \''.$str.'\';');
           
            return  $this_string ;
            //return  '<div style="overflow:hidden;"><input  name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="text" size="150" value="'.$value.'" /></div>';
        }
 

        static function getHTML($articleid, $fieldid, $category = false, $write=false)
        { 
          global $globalreturn;

          //$str = fieldattach::getInput($articleid, $fieldid, $category); 
          $html ='';
          
         

          //if(function_exists( 'fieldattach::getFieldValues' )) 
          if(method_exists ( 'fieldattach' , 'getFieldValues' ))
          {
            $jsonValues       = fieldattach::getFieldValues( $articleid,  $fieldid , $category   );
            $jsonValuesArray  = json_decode($jsonValues); 


            $valor      = html_entity_decode($jsonValuesArray->value);
            $title      = $jsonValuesArray->title;
            $published  = $jsonValuesArray->published;
            $showTitle  = $jsonValuesArray->showtitle;

          }
          else
          {
            $valor      = fieldattach::getValue( $articleid,  $fieldid , $category   );
            $title      = fieldattach::getName( $articleid,  $fieldid , $category  );
            $published  = plgfieldsattachment_input::getPublished( $fieldid  );
            $showTitle  = fieldattach::getShowTitle(   $fieldid  );

          } 


          if(!empty($valor) && $published)
          {
              
              
              $html = plgfieldsattachment_input::getTemplate($fieldid);
              
              /*
                Templating INPUT *****************************
               
                [TITLE] - Title of field
                [FIELD_ID] - Field id 
                [VALUE] - Value of input
               
              */ 
              
              if($showTitle) $html = str_replace("[TITLE]", $title, $html); 
              else $html = str_replace("[TITLE]", "", $html); 
              
              $html = str_replace("[VALUE]", $valor, $html);
              $html = str_replace("[FIELD_ID]", $fieldid, $html);
          }
            
           //WRITE THE RESULT
           if($write)
           {
                echo $html;
           }else{
                $globalreturn = $html;
                return $html; 
           }
        }
        
     
        /**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
    static function getTemplate($fieldsids, $file="input")
    {
         //Search field template GENERIC *****************************************************************
          $templateDir =  dirname(__FILE__).'/tmpl/'.$file.'.tpl.php'; 
          $html = file_get_contents ($templateDir);
          
          //Search field template in joomla Template  ******************************************************  
          $app = JFactory::getApplication();
          $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$file.'.tpl.php';
          
          if(file_exists($templateDir))
          {
               
              $html = file_get_contents ($templateDir);
          }
          
          //Search a specific field template in joomla Template  *********************************************  
          $app = JFactory::getApplication();
          $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$fieldsids.'_'.$file.'.tpl.php';
          
          if(file_exists($templateDir))
          { 
              $html = file_get_contents ($templateDir);
          }
          
          return $html;
    }
    
     

    function action( $articleid, $fieldsid, $fieldsvalueid )
    {

    }
	
	public function searchinput($fieldsid, $value, $extras)
	{
		return plgfieldsattachment_input::renderInput(-1, $fieldsid, $value, $extras);
		  
		  
	}
       

}
