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

global $sitepath;
$sitepath = JPATH_ROOT ;  
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
include_once $sitepath.'/administrator/components/com_fieldsattach/helpers/extrafield.php';

class plgfieldsattachment_select extends extrafield
{
    protected $name;
     
         
	static function construct1( )
	{
        parent::getLanguage(plgfieldsattachment_select::getName());    
	}

    static public function getName()
    {  

          return "select";
             // return  $this->name;
    }
	   

    static function renderInput($articleid, $fieldsid, $value, $extras=null )
    {
        $required="";
        
        global $sitepath; 
        JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
      
        $boolrequired = fieldattach::isRequired($fieldsid);
        if($boolrequired) $required="required";
        
        $tmp = $extras;
        //$str .= "<br> resultado1: ".$tmp;
        $lineas = explode(chr(13),  $tmp);
        //$str .= "<br> resultado2: ".$lineas[0];
        //Add CSS ***********************
        // $str =  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/select/select.css" type="text/css" />'; 
        $app = JFactory::getApplication();
        // $templateDir = JURI::base() . 'templates/' . $app->getTemplate(); 
        
        //FIND SELECT ****************
        $cont = 0;
        $default = -1;
        $selected = -1;
        foreach ($lineas as $linea)
        {
            $tmp = explode('|',  $linea);   
             if(count($tmp)>=2){ 
                $tmpdefault = -1;
                if(count($tmp)>=2) $valor = $tmp[1];
                if(count($tmp)>=3) $tmpdefault = $tmp[2];
                 
                
                if($value == $valor){
                    $selected  = $cont; 
                    break;
                }

                if($tmpdefault == "true"){$default  = $cont; }

                $cont++;
             }
             
        }
         
        //RENDER SELECT **************** 
        $cont = 0; 
        $option_selected = $default;
        if($selected >= 0) {$option_selected = $selected;}
         
        $str="";
        $str  .= '<select name="field_'.$fieldsid.'" class="customfields '.$required.'">';
        $str .= '<option value="">'.JText::_("PLG_SELECT_OPTIONS").'</option> '; 
        foreach ($lineas as $linea)
        {

            $tmp = explode('|',  $linea);
            $title = $tmp[0];
            if(count($tmp)>=2) $valor = $tmp[1];
			else $valor=$title;
            $str .= '<option value="'.stripslashes($valor).'" ';
            //if($value == $valor) $str .= 'selected="selected"'; 
            if(($option_selected) == $cont)  $str .= 'selected="selected"'; 
                
            $str .= ' >';
            $str .= stripslashes($title);
            $str .= '</option>';
            $cont++;
        }
        $str .= '</select>';
        return  $str;
    }

    static function getHTML($articleid, $fieldsid, $category = false, $write=false)
    {
        global $globalreturn;
        //$str  = fieldattach::getSelect($articleid, $fieldsid);
        
          //$valor = fieldattach::getValue( $articleid,  $fieldsid, $category  );
          //$title = fieldattach::getName( $articleid,  $fieldsid , $category );
          //$published = plgfieldsattachment_select::getPublished( $fieldsid  );

          if(method_exists ( 'fieldattach' , 'getFieldValues' ))
          {
            $jsonValues       = fieldattach::getFieldValues( $articleid,  $fieldsid , $category   );
            $jsonValuesArray  = json_decode($jsonValues); 


            $valor      = $jsonValuesArray->value;
            $title      = $jsonValuesArray->title;
            $published  = $jsonValuesArray->published;

          }
          else
          {
            $valor = fieldattach::getValue( $articleid,  $fieldsid, $category  );
            $title = fieldattach::getName( $articleid,  $fieldsid , $category );
            $published = plgfieldsattachment_select::getPublished( $fieldsid  );

          } 

          $html="";
            
          if(!empty($valor) && $published)
          {
              //$isNull= plgfieldsattachment_select::isNull( $fieldsid , $valor, $category );
              $valorselects = fieldattach::getValueSelect( $fieldsid , $valor );
              //echo "<br />ISNULL:".$isNull."---<br/>";
              //if(!$isNull){
              if(!empty($valorselects)){
                    
                    
                    $html = plgfieldsattachment_select::getTemplate($fieldsid, "select");
          
                    /*
                        Templating Laouyt *****************************

                        [TITLE] - Title of field
                        [FIELD_ID] - Field id 
                        [VALUE] - Value of input
                        [ARTICLE_ID] - Article id

                    */
		
		 

                    if(fieldattach::getShowTitle(   $fieldsid  )) $html = str_replace("[TITLE]", $title, $html); 
                    else $html = str_replace("[TITLE]", "", $html); 

                    $html = str_replace("[VALUE]", stripslashes($valorselects), $html);
                    $html = str_replace("[FIELD_ID]", $fieldsid, $html);
                    $html = str_replace("[ARTICLE_ID]", $articleid, $html);

              }else{
                  $html="";
              }
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
        
    /**
	 * Return the value of selectfield
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
	//public function getValueSelect($articleid, $fieldsids, $category = false )
    static public function isNull( $fieldsids, $valor,  $category = false )
	
	{
            //$valor = fieldattach::getValue($articleid, $fieldsids, $category );
            $valortmp = explode(",", $valor);
            
	    $db = JFactory::getDBO(  );

	    $query = 'SELECT  a.extras  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
 
            echo "<br/>  qqq".$query."<br/>";
            $db->setQuery( $query );
	    $extras = $db->loadResult();  
            $retorno = false;
            if(!empty($extras)) {
                   
                   $lineas = explode(chr(13),  $extras); 
                     foreach($lineas as $linea){  
                        $linea = explode("|",  $linea);
                        $value = $linea[0];
                        //if(count($linea)>1){$value = $linea[1];} 
                        
                        if($valor == $value)
                        {
                            if(count($linea)==1) {$retorno = true;break;}
                            
                        }
                            //break;
                    }
                }
            
            
           // echo "VALOR: ".count($tmp)."<br/>";
	    return $retorno;
	}
        
	public function searchinput($fieldsid, $value, $extras)
	{
		return plgfieldsattachment_select::renderInput(-1, $fieldsid, $value, $extras);
		  
		  
	}
        
         

        
       

}
