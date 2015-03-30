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
 
// require helper file
$sitepath = JPATH_ROOT ; 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
include_once $sitepath.'/administrator/components/com_fieldsattach/helpers/extrafield.php';
 
class plgfieldsattachment_textarea extends extrafield
{
    protected $name; 
    
    function plgfieldsattachment_textarea( $subject, $config)
	{
		parent::__construct($subject, $config);
    }

	static function construct1( )
	{
         parent::getLanguage(plgfieldsattachment_textarea::getName());    
            
	}

    static public function getName()
    {  

          return "textarea";
             // return  $this->name;
    }
	  
        

    static function renderInput($articleid, $fieldsid, $value, $extras=null )
    {
        $required="";
        $boolrequired = fieldattach::isRequired($fieldsid);
        if($boolrequired) $required="required";
        
        $tmp = $extras;
        $lineas = explode(chr(13),  $tmp);
        $height = 300;
        $str="";
    
     
        
        //Add CSS ***********************
        $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/textarea/textarea.css" type="text/css" />'; 
        $app = JFactory::getApplication();
        $templateDir = JURI::base() . 'templates/' . $app->getTemplate();
        $css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/textarea.css";
        $pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/textarea.css"; 
        if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 

     
        if($lineas[0] == "RichText")
        {
            $editor = JFactory::getEditor();
            $str .=  $editor->display('field_'.$fieldsid.'', $value , '100%', ''.$height.'', '60', '20', false);

        }else{
             $str .= '<textarea style="width:100%; height:'.$height.'px;" name="field_'.$fieldsid.'" >'.$value.'</textarea>';
    
        }
        
        //$str .= '<script>window.addEvent("load", function() { $("field_'.$fieldsid.'").addClass("'.$required.'"); } );</script>';
        return  $str;
    }

    static function getHTML($articleid, $fieldsid, $category = false, $write=false )
    {
        global $globalreturn;

        if(method_exists ( 'fieldattach' , 'getFieldValues' ))
          {
            $jsonValues       = fieldattach::getFieldValues( $articleid,  $fieldsid , $category   );
            $jsonValuesArray  = (json_decode($jsonValues)); 


	    if(count( $jsonValuesArray )>0)
            { 
            	$valor      = html_entity_decode($jsonValuesArray->value);
            	$title      = $jsonValuesArray->title;
            	$published  = $jsonValuesArray->published;
            	$showTitle  = $jsonValuesArray->showtitle;
	    }

          }
          else
          {
            $valor = fieldattach::getValue($articleid, $fieldsid, $category);
            $title = fieldattach::getName( $articleid,  $fieldsid , $category  );
            $published = plgfieldsattachment_textarea::getPublished( $fieldsid  );
            $showTitle  = fieldattach::getShowTitle(   $fieldsid  );

          }  
        
        $html =""; 
        if(!empty($valor) && $published){ 
            $html = plgfieldsattachment_textarea::getTemplate($fieldsid, "textarea");

            /*
                Templating INPUT *****************************

                [TITLE] - Title of field
                [FIELD_ID] - Field id 
                [VALUE] - Value of input

            */ 

            if($showTitle) $html = str_replace("[TITLE]", $title, $html); 
            else $html = str_replace("[TITLE]", "", $html); 

            $html = str_replace("[VALUE]", $valor, $html);
            $html = str_replace("[FIELD_ID]", $fieldsid, $html);
            $html = str_replace("[ARTICLE_ID]", $articleid, $html);
            
            
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
    

 
     
    function action( $articleid, $fieldsid, $fieldsvalueid)
    {

    }
   

}
