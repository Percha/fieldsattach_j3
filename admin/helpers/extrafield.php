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
        
	public function getLanguage($name)
	{
	    //LOAD LANGUAGE --------------------------------------------------------------
            $lang   =&JFactory::getLanguage();
            $lang->load( 'plg_fieldsattachment_'.$name  );
            $lang = &JFactory::getLanguage(); ;
            $lang_file="plg_fieldsattachment_".$name ;
            $sitepath1 = JPATH_BASE ;
            $sitepath1 = str_replace ("administrator", "", $sitepath1);
            $path = $sitepath1."languages/". $lang->getTag()."/".$lang->getTag().".".$lang_file.".php.ini";
             
            if(JFile::exists($path)){ 
               JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
            }else{
		$path = $sitepath1."languages/en-GB/en-GB.".$lang_file.".php.ini";
		 
		if(JFile::exists($path)){
			echo "SSI".$path;
			JPlugin::loadLanguage( 'plg_fieldsattachment_'.$name );
		}
	    }
	    
	    
	}
	
	  
        public function getName()
        {  
                return  $this->name;
        }

        public function renderHelpConfig(  )
        { 
             
            
            return  $return;
        }



        function renderInput($articleid, $fieldsid, $value , $extras = null)
        {
        }

        function getoptionConfig($valor, $name)
        {
             $name = $this->name;
             $return ='<option value="'.$name.'" ';
             if($name == $valor)   $return .= 'selected="selected"';
             $return .= '>'.$name.'</option>';
             return $return ;
        }

        function getHTML($articleid, $fieldid, $category = false, $write=false)
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
        
        function getPublished( $fieldsids  )
        { 
             
            
	    $db = &JFactory::getDBO(  );

	    $query = 'SELECT  a.published  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
            $return="true|true";
            
            $db->setQuery( $query );
	    $published = $db->loadResult();  
            
            return $published;
        }
	

        function action()
        {

        }
	
	/**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
        function getTemplate($fieldsids,$name="")
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
