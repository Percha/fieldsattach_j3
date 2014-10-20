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
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
 

class extrafield extends JPlugin
{
  protected $name;
	
	
	public function construct( )
	{
              
             
	}

  static public function construct1( )
  {
              
             
  }

  
        
	static public function getLanguage($name)
	{
     //JSON LANGUAGE*************** 
      $lang = JFactory::getLanguage();
      $extension = 'plg_fieldsattachment_'.$name;
      $base_dir = JPATH_ADMINISTRATOR;
      //$language_tag = 'en-GB';
      $language_tag = JFactory::getLanguage();
      $lang = JFactory::getLanguage(); 
      $languages = JLanguageHelper::getLanguages('lang_code'); 
      $language_tag = (isset($languages[$lang->getTag()])) ? $languages[ $lang->getTag() ]->sef : null;

      $reload = true;
      $lang->load($extension, $base_dir, $language_tag, $reload);  
	    
	    
	}
	
	  
  static public function getName()
  {  

      return "input";
         // return  $this->name;
  }

   public function renderHelpConfig(  )
  { 
       
      
      return  $return;
  }



  static function renderInput($articleid, $fieldsid, $value , $extras = null)
  {
  }

  static function getoptionConfig($valor, $name)
  {
        //eval("$name =".$name."::getName()");
       $return ='<option value="'.$name.'" ';
       if($name == $valor)   $return .= 'selected="selected"';
       $return .= '>'.$name.'</option>';
       return $return ;
  }

   
  static function getHTML($articleid, $fieldid, $category = false, $write=false)
  { 
      
  }
        
        /**
	 * getPublish
	 *
	 * @access	public
	 * @param	fieldsids	Id of fields
	 * @return  	published       published or not
	 * @since	1.0
	 */
        
    static function getPublished( $fieldsids  )
    { 
         
        
  $db = JFactory::getDBO(  );

  $query = 'SELECT  a.published  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
        $return="true|true";
        
        $db->setQuery( $query );
  $published = $db->loadResult();  
        
        return $published;
    }


    function action( $articleid, $fieldsid, $fieldsvalueid )
    {

    }
	
	/**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
        static function getTemplate($fieldsids,$name="")
        {
	      if(empty($name)) $name="input";
             //Search field template GENERIC *****************************************************************
              //$templateDir =  dirname(__FILE__).'/tmpl/'.$this->name.'.tpl.php';
	            $templateDir =  JPATH_ROOT.'/plugins/fieldsattachment/'.$name.'/tmpl/'.$name.'.tpl.php';
	       
              $html = file_get_contents ($templateDir);
	      
	      
              
              //Search field template in joomla Template  ******************************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_ROOT . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$name.'.tpl.php';
              
              if(file_exists($templateDir))
              {
                
                  $html = file_get_contents ($templateDir);
              }
              
              //Search a specific field template in joomla Template  *********************************************  
              $app = JFactory::getApplication();
              $templateDir =  JPATH_ROOT . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$fieldsids.'_'.$name.'.tpl.php';
              
              if(file_exists($templateDir))
              { 
                  $html = file_get_contents ($templateDir);
              }
	       
              
              return $html;
        }
       

}
