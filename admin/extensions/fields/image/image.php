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


jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport( 'joomla.plugin.plugin' );
 
// require helper file
global $sitepath;
$sitepath = JPATH_ROOT; 
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
include_once $sitepath.'/administrator/components/com_fieldsattach/helpers/extrafield.php';

class plgfieldsattachment_image extends extrafield
{
	protected $name;
	protected $path1;
	protected $documentpath;
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
         
	static function construct1( ) { 
		 parent::getLanguage(plgfieldsattachment_image::getName());   
	}

	static public function getName()
    {  

          return "image";
             // return  $this->name;
    }
	  
	 
	static function renderInput($articleid, $fieldsid, $value, $extras = null ) {
	
		$required="";
		
		global $sitepath; 
		JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');
		
		$boolrequired = fieldattach::isRequired($fieldsid);
		if($boolrequired) $required="required";
		
		$str="";
		
		//Add CSS ***********************
		$str .=  '<link rel="stylesheet" href="'.JURI::root() .'plugins/fieldsattachment/image/image.css" type="text/css" />'; 
		$app = JFactory::getApplication();
		$templateDir = JURI::base() . 'templates/' . $app->getTemplate();
		$css =  JPATH_SITE ."/administrator/templates/". $app->getTemplate(). "/html/com_fieldsattach/css/image.css";
		$pathcss= JURI::root()."administrator/templates/". $app->getTemplate()."/html/com_fieldsattach/css/image.css"; 
		if(file_exists($css)){ $str .=  '<link rel="stylesheet" href="'.$pathcss.'" type="text/css" />'; } 
		
		$file = $value;
		$selectable="";
		$str .= '<div class="image" style="overflow:hidden;">';
		if(!empty($extras)) {
			$tmp = $extras;
			$lineas = explode(chr(13),  $tmp);
			$str .= '
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			';
			
			foreach ($lineas as $linea) {
				$selectable="";
				$filter="";
				$height="";
				$width=""; 
				$tmp = explode('|',  $linea);
				if(!empty($tmp[0])) $width = $tmp[0];
				if(count($tmp)>=1) if(!empty( $tmp[1])) $height = $tmp[1];
				if(count($tmp)>=2) if(!empty( $tmp[2]) && isset($tmp[2])) $filter = $tmp[2];
				if(count($tmp)>=3) if(!empty( $tmp[3]) && isset($tmp[3])) $selectable = $tmp[3];
				$str .= '<strong>Size: </strong>';
				$str .= (!empty( $width )) ? $width : 'auto';
				$str .= ' x ';
				$str .= (!empty( $height )) ? $height : 'auto';
				if(!empty( $filter )) $str .=  '<br /><strong>Filter: </strong> '.$filter ;
				$str .= '<br /><strong>Type: </strong>';
				$str .= (!empty( $selectable )) ? 'Joomla Image' : 'Upload Image';
			}
			$str .=  '</div>';
		} 
		
		//$path = $this->path1;
		$path = $sitepath .'images'.DS.'documents';
	              
	            
		$documentpath  = JURI::root();
		$documentpath = $documentpath.DS."images".DS."documents";
		$file_url= $documentpath.DS. $articleid .DS.  $file;
		$file_absolute = JPATH_ROOT.DS."images".DS."documents".DS. $articleid .DS.  $file;
		
		if ((JRequest::getVar('option')=='com_categories' )) {
			$file_url = str_replace("documents", "documentscategories", $file_url);
			$file_absolute= str_replace("documents", "documentscategories", $file_absolute);
		}
		
		if($selectable=="selectable") $file_url  =  fieldsattachHelper::getabsoluteURL().$file;
	            
		if ( (file_exists( $file_absolute )  && (!empty($file)))||($selectable && !empty($file))) {
		
			//Name file
			$str .='<p id="image1" class="thumbnail"><img src="'. $file_url.'"';
			if(!empty( $width )) $str .= ' width="'.$width.'" ' ;
			if(!empty( $height )) $str .= ' height="'.$height.'" ';
			$str .= ' alt=" "/></p>';
			
			//Delete
			$str .= '<p style="overflow:hidden;">';
			if($selectable=="selectable") {
				$clear = '
				<button class="btn btn-danger" type="button" name="field_'.$fieldsid.'_delete1" onclick="javascript:$(\'field_'.$fieldsid.'\').value=\'\';$(\'image1\').style.opacity=\'0.4\';">
				'.JTEXT::_("Clear Image").'
				</button>
				';
			}else{
				$str .= '<label class="checkbox" for="field_'.$fieldsid.'_delete">';
				$str .= '<input name="field_'.$fieldsid.'_delete" type="checkbox" /> ';
				$str .= '<strong>'.JTEXT::_("Delete current image on Save").'</strong>';
				$str .= '</label>';
			} 
			$str .= '</p>';
		}else{
			$value="";
		}  
	        
	        if($selectable=="selectable") {
			$str .= '
			<div class="input-append">
				<input name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="text" value="'.$value.'" class="'.$required.'" />
				<a class="modal modal-button btn btn-primary" title="Select Image" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=140&amp;author=&amp;fieldid=field_'.$fieldsid.'&amp;folder=" rel="{handler: \'iframe\', size: {x: 800, y: 500}}">Select Image</a>
			</div>
			';
		}else{
			$str .= " 
			<script type='text/javascript'>
				window.addEvent('domready', function() { 
					//Add check evrent
					$$('#field_".$fieldsid."_upload').addEvent('change', function(e){ 
						var upload =$(this).value; 
						var result = '';
						if(String(upload).length>0 ) { result = upload; }
						$('field_".$fieldsid."').value= result;
					});
				});
			</script>";
		
			$str .= '
			<input name="field_'.$fieldsid.'" id="field_'.$fieldsid.'" type="hidden" value="'.$value.'" class="customfields '.$required.'" />
			&nbsp;
			<input name="field_'.$fieldsid.'_upload" id="field_'.$fieldsid.'_upload" type="file" class="customfields" />
			';
		}
		
		$str .= '</div>';
		$str .= '<script>function jInsertFieldValue(txt, field){ $(field).value= txt ;}</script>';
		
		return  $str;
        }
 

        static function getHTML($articleid, $fieldsid, $category = false, $write=false)
        {
             
           // $str  ='<div id="cel_'.$articleid.'" class="field_'.$fieldsid.'">'.fieldattach::getImg($articleid, $fieldsid,"", $category).'</div>';
            
            $html  = '' ;
            global $globalreturn;
            $directorio = 'documents' ;
             

           //Categories ============================================================================
           if ($category )
           { 
                 $directorio=  'documentscategories';
                
           }

           if(method_exists ( 'fieldattach' , 'getFieldValues' ))
          {
            $jsonValues       = fieldattach::getFieldValues( $articleid,  $fieldsid , $category   );
            $jsonValuesArray  = json_decode($jsonValues); 


            $valor      = $jsonValuesArray->value;
            $title      = $jsonValuesArray->title;
            $published  = $jsonValuesArray->published;
            $showTitle  = $jsonValuesArray->showtitle;

          }
          else
          {
            $valor = fieldattach::getValue( $articleid,  $fieldsid, $category  );
            $title = fieldattach::getName( $articleid,  $fieldsid , $category );
            $published = plgfieldsattachment_select::getPublished( $fieldsid  );
            $showTitle  = fieldattach::getShowTitle(   $fieldid  );

          } 



            
            /*
            $db = JFactory::getDBO(  );
	    	$query = 'SELECT  a.value  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*") AND a.articleid= '.$articleid;
            

            if($category) {
                $query = 'SELECT  a.value  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*") AND a.catid= '.$articleid;
                $directorio = 'documentscategories' ;

            }

            $db->setQuery( $query );
	        $result = $db->loadResult();
	        */
            $file=""; 
            
            //$published = plgfieldsattachment_image::getPublished( $fieldsid  );
              

            if(!empty($valor) && $published) {
                
                $file = $valor;
                 
                
                if (JFile::exists( JPATH_ROOT .DS."images".DS.$directorio.DS. $articleid .DS. $file)  )
                {
                       // $html .=  '<img src="images/'.$directorio.'/'.$articleid.'/'.$result.'" title = "'.$title.'" alt="'.$title.'" />' ;
                        $html = plgfieldsattachment_image::getTemplate($fieldsid, "image");
                        $url = 'images/'.$directorio.'/'.$articleid.'/'.$valor ;
                        
                }else{
                    if (JFile::exists( JPATH_ROOT .DS.$valor)  ){
                        //$html .=  '<img src="'.$result.'" title = "'.$title.'" alt="'.$title.'" />' ;
                        $html = plgfieldsattachment_image::getTemplate($fieldsid, "image");
                        $url = $valor;
                    }
                }
		
		
                
                
                /*
                Templating replace *****************************
               
                [URL] - Url of image
                [FIELD_ID] - Field id 
                [ARTICLE_ID] - Article id
                [TITLE] - Title of field
               
                */ 
                
                if($showTitle) $html = str_replace("[TITLE]", $title, $html); 
                else $html = str_replace("[TITLE]", "", $html); 
                
              
                $html = str_replace("[URL]", $url, $html);
                $html = str_replace("[FIELD_ID]", $fieldsid, $html);
                $html = str_replace("[ARTICLE_ID]", $articleid, $html);
                
            }
            //$html .= '</div>';
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
           $path = $this->path1;
           $documentpath = $this->documentpath;
           
           
           //FIND FOLDER ************************************************************
           $documentpath=  JPATH_ROOT.DS.'images'.DS.'documents';
           $option = JRequest::getVar("option");
            

           //Categories ============================================================================
           if (($option=='com_categories'   ))
           { 
                 $documentpath=  JPATH_ROOT.DS.'images'.DS.'documentscategories';
                
           }
           
           $file = "field_". $fieldsid."_upload";
           fieldsattachHelper::deleteFile($file, $articleid, $fieldsid, $fieldsvalueid, $documentpath);

           if(!empty($_FILES[$file]['tmp_name'])){
            
            //Create folder if not exist ----------------------------
            if(!JFolder::create($documentpath .DS.  $articleid))
            {
                JError::raiseWarning( 100,   JTEXT::_("I haven't created:"). $documentpath .DS.  $articleid );
            }else
            {
                //$app->enqueueMessage( JTEXT::_("Folder created:"). $documentpath .DS. $articleid)   ;

            }
           
           
            $nombreficherofinal = fieldsattachHelper::uploadFile($file, $articleid, $fieldsid, $fieldsvalueid, $documentpath);

            $width =0;
            $height = 0;
            $filter = "";
            $selectable="";
            $nombrefichero="";

            if(!empty($nombreficherofinal)){ 

                    $db = JFactory::getDbo();
                    $query = 'SELECT a.extras FROM #__fieldsattach as a WHERE a.id='.$fieldsid.'';
                    
                    $db->setQuery( $query );
                    $results = $db->loadObject();
                    $tmp ="";
                    if(!empty($results)){
                           $tmp = $results->extras;
                    }
                    $lineas = explode(chr(13),  $tmp);
                    $str .= '<div>';
                    foreach ($lineas as $linea)
                    {
                        $tmp = explode('|',  $linea);
                        $width = $tmp[0];
                        $height = $tmp[1];
                        $filter = $tmp[2];
                        $selectable= $tmp[3];

                        $nombrefichero = $_FILES[$file]["name"];
                    }

                   fieldsattachHelper::resize($nombreficherofinal, $nombreficherofinal, $width, $height, $articleid, $documentpath, $filter);

                }
        }
        }
        
	 

       

}