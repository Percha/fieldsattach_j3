<?php
/**
 * @version		$Id: fieldattach.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die;
 // require helper file
//JLoader::register('fieldsattachHelper',  'components/com_fieldsattach/helpers/fieldsattach.php');
/**
 * FIELDSATTACH component helper.
 */
//abstract class fieldsattachHelper
class fieldsattachHelper
{
        var $body;
        var $str;
        var $str_option;
        var $menuTabstr;
        var $exist;
        var $exist_options;
	
	/**
	 * Configure the Link bar.
	 */
	public static function addSubmenu($submenu)
	{
		JSubMenuHelper::addEntry(JText::_('COM_FIELDSATTACH_SUBMENU_MESSAGES'), 'index.php?option=com_fieldsattach', $submenu == 'fieldsattachs');
		JSubMenuHelper::addEntry(JText::_('COM_FIELDSATTACH_SUBMENU_GROUPS'), 'index.php?option=com_fieldsattach&view=fieldsattachgroups', $submenu == 'fieldsattachgroups');

                JSubMenuHelper::addEntry(JText::_('COM_FIELDSATTACH_SUBMENU_UNIDADES'), 'index.php?option=com_fieldsattach&view=fieldsattachunidades', $submenu == 'fieldsattachunidades');
		JSubMenuHelper::addEntry(JText::_('COM_FIELDSATTACH_SUBMENU_BACKUP'), 'index.php?option=com_fieldsattach&view=fieldsattachbackup', $submenu == 'fieldsattachbackup');
		 
		/*ADD ONS*/
		jimport( 'joomla.filesystem.folder' );
		
		/*SELECTOR TREE*/
		$folder = JPATH_ROOT.'/administrator/components/com_fieldsattachselector';
		 
		$exist = false;
		if (JFolder::exists($folder)) {$exist = true;}
		
		 
		if($exist) JSubMenuHelper::addEntry(JText::_('COM_FIELDSATTACH_SUBMENU_TREE'), '	index.php?option=com_fieldsattachselector&view=categories&extension=com_fieldsattach', $submenu == 'fieldsattachselecttree');
		 
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_fieldsattach';
		}
		else {
			$assetName = 'com_fieldsattach.message.'.(int) $messageId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

        /**
	 * Get a list of the user groups for filtering.
	 *
	 * @return	array	An array of JHtmlOption elements.
	 * @since	1.6
	 */
	static function getGroups()
	{
		$db = JFactory::getDbo();

		$db->setQuery(
			'SELECT a.id AS value, a.title AS text FROM #__fieldsattach_groups as a ORDER BY  a.title'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}
                if($options>0){
                    foreach ($options as &$option) {
                            $option->text = $option->text;
                            $option->value =  $option->value  ;
                    }
                }

		return $options;
	}

        

         /**
	 * Get a list of the user groups for filtering.
	 *
	 * @return	array	An array of JHtmlOption elements.
	 * @since	1.6
	 */
	public function getGallery($articleid, $fieldsattachid )
	{
            
        // Load the tooltip behavior. 


        $db = JFactory::getDbo();
        $directory = "administrator/";
        if ( JFactory::getApplication()->isAdmin()) {
             $directory = "";
        }

        $query = $db->getQuery(true);
         // Select some fields
		$query->select('*');

		// From the hello table
		$query->from('#__fieldsattach_images');
        $query->where("articleid = ".$articleid." AND fieldsattachid=".$fieldsattachid);

        $query->order("ordering");
 
               // $db = JFactory::getDbo();

		 $db->setQuery($query);
		 //$str = $query;
		 $rows= $db->loadObjectList();
		 	$str = '<div style="position:relative; float:left; top:-50px;"><a class=\'modal\' rel=\'{handler: "iframe", size: {x: 980, y: 500}}\' href=\''.JURI::base(false).'/'.$directory.'index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2\'></a>
 			';
            $str .= "<a href='#' onclick='update_gallery".$fieldsattachid."();return false;'><img src='".JURI::base(false)."/".$directory."components/com_fieldsattach/images/icon-refresh.png' alt='refresh' /></a>";
			//$str .= "<a class='modal' rel='{handler: \"iframe\", size: {x: 980, y: 500}}' href='index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2&fieldsattachid=".$fieldsattachid."'><img src='components/com_fieldsattach/images/icon-32-new.png' alt='refresh' /></a>";
			$str .= '</div>';
         

             $str .= "<ul style=' overflow:hidden;'>";
             $sitepath  =  fieldsattachHelper::getabsoluteURL(); 
             if($rows>0){
               foreach ($rows as $row)
                {
                  //$url_edit ='index.php?option=com_fieldsattach&amp;task=fieldsattachimage.edit&amp;id='.$row->id.'&amp;tmpl=component&amp;reset=2&amp;fieldsattachid='.$fieldsattachid.'&amp;direct=true';
				  $url_edit =JURI::base(false).'/'.$directory.'/index.php?option=com_fieldsattach&view=fieldsattachimage&tmpl=component&layout=edit&id='.$row->id.'&fieldsattachid='.$fieldsattachid.'&reset=2';
				  $url_delete =JURI::base(false).'/'.$directory.'/index.php?option=com_fieldsattach&amp;view=fieldsattachimages&amp;task=delete&amp;id='.$row->id.'&amp;tmpl=component&amp;fieldsid='.$fieldsattachid;
                  $str.= '<li style="width:150px; height:150px; margin: 0px 10px 10px 0; overflow:hidden; float:left; border:1px solid #ddd;">
                  <div style="overflow:hidden;margin-bottom:8px;"><div style="width:32px;float:left;"> <a class="modal" href="'.$sitepath.''.$row->image1.'"><img src="'.JURI::base(false).'/'.$directory.'components/com_fieldsattach/images/icon-zoom.png" alt="zoom" /></a>
                  </div>
                  <div style="width:32px;float:right;"><a class="modal" href="'.$url_delete.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}"><img src="'.JURI::base(false).'/'.$directory.'components/com_fieldsattach/images/icon-32-delete.png" alt="zoom" /></a>
                  </div></div>
               	  <div><a class="modal"  href="'.$url_edit.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}" ><img src="'.$sitepath.''.$row->image1.'" alt="'.$row->title.'" width="150" /></a>
                  </div>
                  </li>';

                }
             }
			$str .= "<li style='width:80px; background:url(".JURI::base(false)."/".$directory."components/com_fieldsattach/images/icon-32-new.png) no-repeat 50px 40px; height:10px; margin: 0px 10px 10px 0; overflow:hidden; float:left; border:1px solid #ddd;padding:80px 20px 60px 35px;'>
			<a class='modal' rel='{handler: \"iframe\", size: {x: 980, y: 500}}' href='".JURI::base(false)."/".$directory."/index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2&fieldsattachid=".$fieldsattachid."'>";
			$str .= JText::_("NEW IMAGE").'</a></li>';
		
            $str .= "</ul>";
				


		return  $str;
	}

    /**
	 * Get Form XML for edit parameters and HELP	 *
	 * @return	string	An array of JHtml FORM elements.
	 * @since	1.6
	*/
    static public function getForm($name)
    {
        jimport( 'joomla.form.form' );
        $doc = JFactory::getDocument();
        
        $return = "" ;
        //Load XML FORM ==================================================
        //$file = dirname(__FILE__) . DS . "form.xml";
        $file = JPATH_PLUGINS.DS.'fieldsattachment'.DS.$name.DS.'form.xml';
        // echo "FILEWWWW:".$file;
        //$form = $this->form->loadfile( $file ); // to load in our own version of login.xml
        //$obj = new JForm();
       
       //  $form = JForm::getInstance($data, "string");
        //$form = JForm::getInstance("form", $file,  true);
        $options = array();
        $cform = JForm::getInstance('com_fieldsattach', $file, array(), true, 'component');
        $cform->loadFile($file, true,  false);
        //$xml = $cform->getXml(); 
       
       
        //$form = $obj->form->loadfile( $file , true, true); // to load in our own version of login.xml
        if($cform){
           
            //$form = $this->form->getFieldset("parametros_".$name);
            $form = $cform->getFieldset("parametros_".$name);
            //$return .= JHtml::_('sliders.panel', JText::_( "JGLOBAL_FIELDSET_HELP_AND_OPTIONS"), "percha_".$name.'-params');
            $return .= '<div id="percha_'.$name.'-params" class="extraoptioninfo">';
            $return .= '<fieldset id="wrapperextrafield_'.$name.'"  >';
             $return .=   '           <ul class="adminformlist" style="overflow:hidden;">';
            // foreach ($this->param as $name => $fieldset){
            foreach ($form as $field) {
                $return .=   "<li>".$field->label ." ". $field->input."</li>";
            }
            $return .='</ul>';
            
            if(count($form)>1){
            $return .=  '<div><input class="updatebutton" type="button" value="'.JText::_("Update Config").'" onclick="controler_percha_'.$name.'()" /></div>';
            }
            $return .=  '</fieldset></div>';
            //$return .=  '<script src="'. JURI::root().'plugins/fieldsattachment/'.$name.'/js/controler.js" type="text/javascript"></script> ';
            
            //Hide OPTIONS *********** JOOMLA 3
            //$return .=  '<script type="text/javascript">window.addEvent("domready", function() {controler_percha_'.$name.'();});</script>';
            
        }
        
        //LANGUAGE FILE
        $lang   =JFactory::getLanguage();
        $lang->load( 'plg_fieldsattachment_'.$name  );
        $lang = JFactory::getLanguage(); ;
        $lang_file="plg_fieldsattachment_".$name ;
        $sitepath1 = JPATH_ROOT ;
        //$sitepath1 = str_replace ("administrator", "", $sitepath1);
        $path = $sitepath1."/administrator/language".DS . $lang->getTag() .DS.$lang->getTag().".".$lang_file.".ini";
        
        //LOAD JS
        //$doc = new DOMDocument();
        //$doc->loadXML($path);
        //$doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
         
         

        
        
        //LOAD JS -- 25-09-2012
        //$xml = JFactory::getXMLParser('Simple');
        
        $xmlfile = JPATH_PLUGINS.DS.'fieldsattachment'.DS.$name.DS.$name.'.xml'; 
        //$xml->loadFile($xmlfile); 
        //$xml = JFactory::getXMLParser('Simple');
        //$xml = SimpleXMLElement($xmlfile);
        //echo "FILE::".$xmlfile;
        $dom = new DOMDocument(); 
        
        // return, if file does not exists, to avoid errors when loading XML File. RH, 16.01.2015
		if (!file_exists($xmlfile)) return $return;
        
        $dom->load($xmlfile); 
        $xml = $dom->getElementsByTagName('filename'); 
        foreach($xml as $ph){ 

            $file = $ph->nodeValue; 
             
            if(strrpos ( $file , ".js" )){
                    if(strrpos ( $file , ".js.php" )){
                        $doc->addScript(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$file."?dictionary=".$path);
                    }else{
                        $doc->addScript(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$file);
                    }
                }
                
                if(strrpos ( $file , ".css" )){
                
                $doc->addStyleSheet(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$file);
                }

        } 
         

        //$xml = new SimpleXMLElement($obj);
        /*if ($xml->loadFile($xmlfile)) {
        // We can now step through each element of the file 
        foreach( $xml->document->files as $files ) {
             foreach( $files->filename as $filename ) {
                //$file = $filename->getElementByPath('filename'); 
                //$return .= "FILE: {$filename->data()}<br/>"; 
                $file = $filename->data();
                if(strrpos ( $file , ".js" )){
                    if(strrpos ( $file , ".js.php" )){
                        $doc->addScript(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$filename->data()."?dictionary=".$path);
                    }else{
                        $doc->addScript(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$filename->data());
                    }
                }
                
                if(strrpos ( $file , ".css" )){
                
                $doc->addStyleSheet(JURI::root().'/plugins/fieldsattachment/'.$name.'/'.$filename->data());
                }

             }
        }
        }
        else {
            
        }*/

        return $return;
    }

        /**
	 * UPLOAD A FILE	 *
	 * @return	nothing
	 * @since	1.6
	 */
        function uploadFile($file, $articleid, $fieldsid,  $fieldsvalueid,  $path = null)
        {
            if(!empty($_FILES[$file]['tmp_name'])){
            $SafeFile = $_FILES[$file]['name'];

            $SafeFile = str_replace("#", "No.", $SafeFile);
            $SafeFile = str_replace("$", "Dollar", $SafeFile);
            $SafeFile = str_replace("%", "Percent", $SafeFile);
            $SafeFile = str_replace("^", "", $SafeFile);
            $SafeFile = str_replace("&", "and", $SafeFile);
            $SafeFile = str_replace("*", "", $SafeFile);
            $SafeFile = str_replace("?", "", $SafeFile);

            // JError::raiseWarning( 100, $file. " NAMETMP:".$SafeFile." ID:: ". $articleid. " ->  fieldsid ".$fieldsid ." PATH:".$path  );
            //JError::raiseWarning( 100,   $path .DS. $articleid .DS.  $_FILES[$file]["name"] );
             
            if(!JFile::upload($_FILES[$file]['tmp_name'] , $path .DS. $articleid .DS.  $_FILES[$file]["name"]))
            {
                JError::raiseWarning( 100,  JTEXT::_("Uploda image Error")   );
            }else
            {
                $app = JFactory::getApplication();
                $app->enqueueMessage( JTEXT::_("Uploda image OK")  );
                $nombreficherofinal = $_FILES[$file]["name"];
                if (file_exists( $path .DS. $articleid .DS. $nombreficherofinal))
                {

                    //$nombreficherofinal = $fieldsid."_".$nombreficherofinal;
                    $app->enqueueMessage( JTEXT::_("Name image changed " ). $nombreficherofinal  );
                    //JError::raiseWarning( 100, $_FILES[$file]["name"]. " ". JTEXT::_("already exists. "). " -> Name changed ".$nombreficherofinal   );
                    JFile::move($path .DS. $articleid .DS.$_FILES[$file]["name"], $path .DS. $articleid .DS.$nombreficherofinal);
                }
                //UPDATE
                $db	= & JFactory::getDBO();
                if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit"   ))
                {
                    $query = 'UPDATE  #__fieldsattach_categories_values SET value="'. $nombreficherofinal.'" WHERE id='.$fieldsvalueid ;
                }else{
                     $query = 'UPDATE  #__fieldsattach_values SET value="'. $nombreficherofinal.'" WHERE id='.$fieldsvalueid ;
                }
               
                $db->setQuery($query);
                $db->query();

                return $nombreficherofinal;
            }
            }
        }

        function deleteFile($file, $articleid, $fieldsid,  $fieldsvalueid,  $path = null)
        {
            $deletefile = JRequest::getVar("field_". $fieldsid.'_delete');
            $file = JRequest::getVar("field_". $fieldsid);

            if($deletefile){

                     //echo $this->path .DS. $file ;
                     $deleted= false;
                     if(empty($selectable)){
                         if(!JFile::delete( $path .DS. $articleid .DS.  $file) )
                         {
                              JError::raiseWarning( 100,  JTEXT::_("Delete file Error")." ".$path   );

                         } else
                         {
                             $deleted = true;
                         }
                     }
                     if((!empty($selectable)||($deleted)))
                        {

                            //UPDATE
                            $db	= & JFactory::getDBO();
                            $query = 'UPDATE  #__fieldsattach_values SET value="" WHERE fieldsid='.$fieldsid. ' AND articleid='.$articleid ;
                            $db->setQuery($query);
                            $db->query();
                            $app = JFactory::getApplication();
                            $app->enqueueMessage( JTEXT::_("Delete image")   );


                        }

                    }
        }
        
        
        /*resizeImg function*/
        
        function resizeImg($img, $w, $h, $newfilename,$filter=null) {

            
        $app = JFactory::getApplication();
        
        
        $app->enqueueMessage( JTEXT::_("IMAGE RESIZE: ")." width:".$w." height:".$h  );
            
            
        //Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
        }

        //Get Image size info
        $imgInfo = getimagesize($img);
        switch ($imgInfo[2]) {
        case 1: $im = imagecreatefromgif($img); break;
        case 2: $im = imagecreatefromjpeg($img);  break;
        case 3: $im = imagecreatefrompng($img); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
        }
        
        //FILTER
        if(!empty($filter))
        { 
                if($filter =="IMG_FILTER_NEGATE") $filter_num = 0;
                if($filter =="IMG_FILTER_GRAYSCALE") $filter_num = 1;
                if($filter =="IMG_FILTER_BRIGHTNESS") $filter_num = 2;
                if($filter =="IMG_FILTER_CONTRAST") $filter_num = 3;
                if($filter =="IMG_FILTER_COLORIZE") $filter_num = 4;
                if($filter =="IMG_FILTER_EDGEDETECT") $filter_num = 5;
                if($filter =="IMG_FILTER_EMBOSS") $filter_num = 6;
                if($filter =="IMG_FILTER_GAUSSIAN_BLUR") $filter_num = 7;
                if($filter =="IMG_FILTER_SELECTIVE_BLUR") $filter_num = 8;
                if($filter =="IMG_FILTER_MEAN_REMOVAL") $filter_num = 9;
                if($filter =="IMG_FILTER_SMOOTH") $filter_num = 10;
                if($filter =="IMG_FILTER_PIXELATE") $filter_num = 11;
                if(imagefilter($im, $filter_num, 50))
                {
                    $app->enqueueMessage( JTEXT::_("Apply filter:").$filter_num  );
                }  else {
                    JError::raiseWarning( 100,  JTEXT::_("Apply filter ERROR:").$filter_num   );
                }

        }

        //If image dimension is smaller, do not resize
        if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
        }else{
                        //yeah, resize it, but keep it proportional
        if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
        $nWidth = $w;
        $nHeight = $imgInfo[1]*($w/$imgInfo[0]);
        }else{
        $nWidth = $imgInfo[0]*($h/$imgInfo[1]);
        $nHeight = $h;
        }
        }
        $nWidth = round($nWidth);
        $nHeight = round($nHeight);

        $newImg = imagecreatetruecolor($nWidth, $nHeight);

        /* Check if this image is PNG or GIF, then set if Transparent*/  
        if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
        imagealphablending($newImg, false);
        imagesavealpha($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

        //Generate the file, and rename it to $newfilename
        switch ($imgInfo[2]) {
        case 1: imagegif($newImg,$newfilename); break;
        case 2: imagejpeg($newImg,$newfilename);  break;
        case 3: imagepng($newImg,$newfilename); break;
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
        }
 
        }

         //IMAGE RESIZE FUNCTION FOLLOW ABOVE DIRECTIONS
        public function resize($nombre,$archivo,$ancho,$alto,$id, $path, $filter=NULL)
        {
            $path_absolute = JPATH_BASE ;
            $app = JFactory::getApplication();

            $arr1 = explode(".", $nombre );
            $tmp = $arr1[1];

            //$nombre = $path_absolute."/".$path .DS. $id .DS. $nombre;
            $nombre =  $path .DS. $id .DS. $nombre;
            $destarchivo = $path .DS. $id .DS. $archivo;
            //$archivo =  $path_absolute."/".$path .DS. $id .DS. $archivo;
            $archivo =  $path .DS. $id .DS. $archivo;

            //$app->enqueueMessage( JTEXT::_("Name file:  ").$nombre);
            //$app->enqueueMessage( JTEXT::_("New name:  ").$archivo);

            if(!file_exists($archivo)){
                JError::raiseWarning( 100, JTEXT::_("Not file exist ")  );
            }
            
            fieldsattachHelper::resizeImg($archivo,$ancho, $alto, $archivo ,$filter );
        }

         //GET URL absolute
        static public function getabsoluteURL()
        {
            $sitepath = JURI::base() ;
            $pos = strrpos($sitepath, "administrator");
            if(!empty($pos)){
                   // $sitepath  = JURI::base().'..'.DS;
                   // echo $sitepath."<br>";
                    $sitepath = str_replace ("administrator/", "", $sitepath);
                   // echo $sitepath."<br>";
                    }
            return $sitepath;
        }

         //GET PATH absolute
        public function getabsolutePATH()
        {
            $sitepath = JPATH_BASE ;
            echo "";
            $pos = strrpos($sitepath, "administrator");
            if(!empty($pos)){
              //  echo "<br>ENTRAAAAAAAAAAAAAAAAAAAAAA: ".$sitepath;
                $sitepath = str_replace ("/administrator", "", $sitepath);
                //echo "<br>sale: ".$sitepath;
            }
            return  $sitepath;
        }

        /**
    	* Arrauy    get fields for a id
    	*
    	* @access	public
    	* @since	1.5
    	*/
        static public function  getfieldsForAll($id)
        {

            $db	=  JFactory::getDBO();
            $empty = array();
            $result = array();

            if(!empty($id))
            {
                $query = 'SELECT a.catid, a.language FROM #__content as a WHERE a.id='. $id  ;

                $db->setQuery( $query );
                $elid = $db->loadObject();
                
                if(!empty($elid)){
                    $idioma = $elid->language; 
                   
                    $db = JFactory::getDBO();
                    $query = 'SELECT a.access as access, a.id as idgroup, a.title as titlegroup ,  a.description as descriptiongroup, a.position, a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                    $query .= 'WHERE a.catid = 0 AND a.published=1 AND b.published = 1 AND a.group_for=0 ';
                    //echo $elid->language."Language: ".$idioma;
                    if($elid->language != "*") $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                          // echo "filter::". $app->getLanguageFilter();
                          // echo "filter::". JRequest::getVar("language");

                    $query .='ORDER BY a.ordering, a.title, b.ordering';
                     
                    $db->setQuery( $query );
                    $result = $db->loadObjectList();
                }
            }
            
            if($result) return $result;
            else return $empty  ;
        }

         /**
	* Arrauy    get fields for a id
	*
	* @access	public
	* @since	1.5
	*/
        public function  getfieldsForAllCategory($id)
        {

            $db	= JFactory::getDBO();
            $query = 'SELECT  a.language FROM #__categories as a WHERE a.id='. $id  ;

            $db->setQuery( $query );
            $elid = $db->loadObject();
            $empty = array();
            $result = array();
            if(!empty($elid)){
                $idioma = $elid->language;


                $db	= & JFactory::getDBO();
                $query = 'SELECT a.access as access, a.id as idgroup, a.title as titlegroup ,  a.description as descriptiongroup, a.position, a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid = 0 AND a.published=1 AND b.published = 1 AND a.group_for=1  ';
                //echo $elid->language."Language: ".$idioma;
                if($elid->language != "*")  $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");

                $query .='ORDER BY a.ordering, a.title, b.ordering';
                //echo $query;
                $db->setQuery( $query );
                $result = $db->loadObjectList();
            }
            if($result) return $result;
            else return $empty  ;
        }

        /**
    	* Get list of fields to content
    	*
    	* @access	public
    	* @since	1.5
    	*/
        static public function getfields($id, $catid=null)
        {
            
            $result = array();

            $db	= JFactory::getDBO();

            if(empty($catid)){
                $query = 'SELECT a.catid, a.language FROM #__content as a WHERE a.id='. $id  ;
                $db->setQuery( $query );
                $elid = $db->loadObject();
                if(!empty($elid)){
                    $idioma = $elid->language;
                    $catid =  $elid->catid;
                    }
            }else{
                //Si tengo el catid impuesto cuando creo un nuevo artículo
                $query = 'SELECT a.language FROM #__content as a WHERE a.id='. $id.' AND catid IN (  '.$catid.' )'  ;
                $db->setQuery( $query );
                $elid = $db->loadObject();
                if(!empty($elid)){
                    $idioma = $elid->language;
                    }
            }
            global $retorno_recursive;
            //$tmpcatid = explode(',', $catid);
            //foreach($tmpcatid as $catid){
            $idscats = fieldsattachHelper::recursivecat($catid,   "");
			$idscats = $retorno_recursive;
			//echo "RECURSIVO:: ".$retorno;
           // JError::raiseNotice(500, "ID:: ".$idscats);
           
            
            if(!empty($elid)){
                
                //Extract all groups idgrou and idcat and put into array
                  $query = 'SELECT a.access as access, a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                  $query .= 'WHERE  a.published=1 AND b.published = 1 AND a.group_for = 0 ';
                  //echo $elid->language."Language: ".$idioma;
                  if (  ($elid->language == $idioma ) && ($elid->language != "*") ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                  }
                  $query .='ORDER BY a.ordering, a.title, b.ordering';
                  //echo "<br>".$query."<br>";
                  $db->setQuery( $query );
                  $result = $db->loadObjectList();
                  if($result>0)
                    {
                       // echo "<br>IDS FIELDS: ".count($result);
                        //**********************************************************************************************
                        //Mirar cual de los grupos es RECURSIVO  ************************************************
                        //***********************************************************************************************
                        $cont = 0;
                        
                        foreach ($result as $field)
                        {
                           $array_idcats_group = explode( "," , $field->catid);
                           $array_idcats = explode( "," , $idscats); 
                           $find = false;
                           foreach($array_idcats_group as $idcatgroup)
                           {
                              if( $idcatgroup == $catid ){
                                  $find = true;
                                  break;
                              }
                              if($field->recursive)
                                      {
                                          foreach($array_idcats as $idcatrecursive)
                                            {
                                                if( $idcatgroup == $idcatrecursive ){
                                                      $find = true;
                                                      break;
                                                  }
                                            }
                                      } 

                            } //foreach
                            if(!$find){unset($result[$cont]);}
                            $cont++;
                        }
                    } 
                 
                //if

                //----------------------------        
            
                
/*
                $query = 'SELECT a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid IN ('. $idscats .') AND a.published=1 AND b.published = 1 AND a.group_for = 0 ';
                //echo $elid->language."Language: ".$idioma;
                if (  ($elid->language == $idioma ) ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                }
                $query .='ORDER BY a.ordering, a.title, b.ordering';
                echo "<br>".$query."<br>";
                $db->setQuery( $query );
                $result = $db->loadObjectList();

                //**********************************************************************************************
                //Mirar cual de los grupos es RECURSIVO  ************************************************
                //***********************************************************************************************
                $cont = 0;
                foreach ($result as $field)
                {

                    if( $field->catid != $catid )
                    {
                        //Mirar si recursivamente si
                        if(!$field->recursive)
                            {
                                //echo "ELIMINO DE LA LISTA " ;
                                unset($result[$cont]);
                            }
                    }
                    $cont++;
                } 
                */
                //return $result;
            }
           /* echo "<br>IDS FIELDS: ".count($result);
            foreach($result as $obj)
                {
                echo " ".$obj->id.", ";
            }*/
            return $result;
        }

          /**
	* Get list of fields to category
	*
	* @access	public
	* @since	1.5
	*/
        public function getfieldsCategory($catid)
        {

            $result = array();
            $db	= JFactory::getDBO();
            $query = 'SELECT a.id, a.language FROM #__categories as a WHERE a.id='. $catid  ;
            $src="";

            $db->setQuery( $query );
            $elid = $db->loadObject();
            $idioma = $elid->language;
            //$this->recursivecat($elid->id, "");
            //fieldsattachHelper::recursivecat($elid->id, & $src);
            global $retorno_recursive;
            $idscats = fieldsattachHelper::recursivecat($elid->id);
			$idscats = $retorno_recursive;
            
            if(!empty($elid)){
                $db	= JFactory::getDBO();

                //Extract all groups idgrou and idcat and put into array
                  $query = 'SELECT a.access as access, a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                  $query .= 'WHERE  a.published=1 AND b.published = 1 AND a.group_for = 1 ';
                  //echo $elid->language."Language: ".$idioma;
                  if (  ($elid->language == $idioma ) && ($elid->language != "*")  ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                  }
                  $query .='ORDER BY a.ordering, a.title, b.ordering';
                  //echo "<br>".$query."<br>";
                  $db->setQuery( $query );
                  $result = $db->loadObjectList();
                  if($result>0)
                    {
                       // echo "<br>IDS FIELDS: ".count($result);
                        //**********************************************************************************************
                        //Mirar cual de los grupos es RECURSIVO  ************************************************
                        //***********************************************************************************************
                        $cont = 0;

                        foreach ($result as $field)
                        {
                           $array_idcats_group = explode( "," , $field->catid);
                           $array_idcats = explode( "," , $idscats);
                           $find = false;
                           foreach($array_idcats_group as $idcatgroup)
                           {
                              if( $idcatgroup == $catid ){
                                  $find = true;
                                  break;
                              }
                              if($field->recursive)
                                      {
                                          foreach($array_idcats as $idcatrecursive)
                                            {
                                                if( $idcatgroup == $idcatrecursive ){
                                                      $find = true;
                                                      break;
                                                  }
                                            }
                                      }

                            } //foreach
                            if(!$find){unset($result[$cont]);}
                            $cont++;
                        }
                    } 
                 
               // return $result;
            }
             return $result;

        }

    /**
	* recursive function
	*
	* @access	public
	* @since	1.5
	*/
        static public function recursivecat($catid, $idscats = "")
        {
        	global $retorno_recursive;
             //JError::raiseNotice(500, "CATID:: ".$catid." - ".$idscats);
             if(!empty($catid)){
                if(!empty($idscats)) $idscats .=  ",";
                $idscats .= $catid ;
                $db	=  JFactory::getDBO();
                $query = 'SELECT parent_id FROM #__categories as a WHERE a.id='.$catid   ;
				//echo "<br>SQL:: ".$query."<br>";
                $db->setQuery( $query );
                $parent_id = $db->loadResult();
                //echo "PARENT:: ".$parent_id."<br>";
                if(!empty($parent_id)) {
                	//echo "<br>".$idscats;
                    fieldsattachHelper::recursivecat($parent_id,  $idscats); 
                }else{
                    // echo "<br>retorno:".$idscats."<br />";
					 $retorno_recursive = $idscats;
             		 return $idscats;
                }
             }
			
        }
		
	 
        /**
    	* Array  HTML get fields for a id
    	*
    	* @access	public
    	* @since	1.5
    	*/
        static public function getfieldsForArticlesid($id, $fields = null)
        {

            $db	=  JFactory::getDBO();
            $empty = array();
            $result = array();

            if(!empty($id))
            {
                    $query = 'SELECT a.catid, a.language FROM #__content as a WHERE a.id='. $id  ;

                    $db->setQuery( $query );
                    $elid = $db->loadObject();
                   
                    if(!empty($elid)){
                    $idioma = $elid->language;

               
                    //$id = ",".$id.",";
                    $db = JFactory::getDBO();

                    $query = 'SELECT a.access as access, a.id as idgroup, a.title as titlegroup ,  a.description as descriptiongroup ,a.position, a.catid, a.language, a.recursive, b.*, a.articlesid FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                    //$query .= 'WHERE (a.articlesid LIKE "%,'. $id .',%" )  AND a.published=1 AND b.published = 1 ';
                    $query .= 'WHERE  a.published=1 AND b.published = 1 ';

                    if($elid->language != "*")  $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;

                    $query .='ORDER BY a.ordering, a.title, b.ordering';
                    //echo $query;
                    $db->setQuery( $query );

                    //(a.articlesid LIKE "%,'. $id .',%" )  AND
                    $results = $db->loadObjectList();

                    //echo "<br>count: " . count($results);
                    $cont = 0;
                    if($results)
                    {
                        foreach($results as $result)
                        {
                            $taula =  explode(",", $result->articlesid);
                            //echo "<br>srting:: ". $result->id;
                            //echo "<br>contar taula:: ". count($taula);
                            $trobat = false;
                            foreach ($taula as $theid)
                            {
                                //echo "<br>buscando: " . $theid;
                                if($theid == $id){
                                    $trobat = true;
                                   // echo "<br>trobat: " . $theid;
                                    break;
                                }
                                else{
                                    $trobat = false;
                                    }
                            }
                            if(! $trobat){
                                unset($results[$cont]);
                            }else{
                                //Find in the fields,   exist?
                                if($fields){
                                    foreach($fields as $obj)
                                    {
                                        if($result->id == $obj->id) unset($results[$cont]);
                                    }
                                }
                            }
                            $cont++;

                        }
                        return $results;
                    }
                }

            } 
            
	        return $empty;


        }

        /**
	* Get a Title of content
	*
	* @access	public
	* @since	1.5
	*/
        function getTitle($id)
        {
		$str="";
		if(!empty($id)){
			$db	= & JFactory::getDBO();
			$query = 'SELECT title FROM #__content as a WHERE a.id='.$id   ;
			//echo $query."<br>";
			$db->setQuery( $query );
			$tmp = $db->loadResult();
			$str ="";
			if(!empty($tmp))  $str =$tmp;
		}
		return $str;
        }
        
        /**
	* Get a inputs of content
	*
	* @access	public
	* @since	1.5
	*/
        function getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options, & $body = null, &  $str = null, &  $str_options= null)
        {
            $str = '';
            $this->exist = false;
            $this->exist_options = false;
            //Menu tabulador
            $menuTabstr=''; 
           
            if(count($fields)>0){
                 $sitepath  =  fieldsattachHelper::getabsoluteURL();

                 // if(JRequest::getVar("view")=="fieldsattachunidad") $str = '<script src="'.$sitepath.'plugins/system/fieldsattachment/js/fieldattachment.js" type="text/javascript"></script> ';
                 if($backend || $fontend )  {
                     $str = '<script src="'.$sitepath.'plugins/system/fieldsattachment/js/fieldattachment.js" type="text/javascript"></script> ';
                     //$str_options = '<script src="'.$sitepath.'plugins/system/fieldsattachment/js/fieldattachment.js" type="text/javascript"></script> ';

                 } 
                  
                  $idgroup="";
                 //TABS RENDER AFTER DESCRIPTION ====================================================================
                 if(count($fields)>0){
                     $this->exist = false;

                     foreach($fields as $field)
                        { 
                         
                            if($field->idgroup != $idgroup){ 
                                  
                                     
                                         $menuTabstr .= '<li class="addtab"><a href="#fiedlsattachTab_'.$field->idgroup.'" data-toggle="tab" >'. $field->titlegroup.'</a></li>';
                                         $this->exist =true;
                                      
                                  

                                $idgroup = $field->idgroup;
                            }

                        }
                    if(!$this->exist ) {
                        //after $str = "" <-- LOOK because
                        //$str  .="";

                        }
                 }
                 
                 
		//INPUTS ================================================
                 
                $this->exist=false; 

		//inputs RENDER ====================================================================
		$idgroup=-1;
		$this->exist  = false;
		$cont = -1;
		$cuantos_en_str=0;
		$field->position=1;
		  
		
		if(count($fields)>0){ 
		foreach($fields as $field)
		  {
		    
		    //if($field->position == 1){
			  $cont++;
			  if($field->idgroup != $idgroup){
			       
			      if($idgroup > 0) { 
				       
				  if($this->exist ==true)  $str .= '</div> '; 
			      } 
				  
			      $this->exist =true;
			      $cuantos_en_str++;

			      $str .= '<div class="tab-pane" id="fiedlsattachTab_'.$field->idgroup.'"  >';
			      if(!empty($field->descriptiongroup)) $str .= '<div class="desc">'.$field->descriptiongroup.'</div>';

			      
			  }
			  $idgroup = $field->idgroup; 
			       
			  
			  //NEW GET PLUGIN ********************************************************************
			  JPluginHelper::importPlugin('fieldsattachment'); // very important
			  //select
			  //$this->array_fields = $this->params->get( 'array_fields' );
			  if(empty($this->array_fields)) $this->array_fields = fieldsattachHelper::get_extensions() ;
			 
			  if(count($this->array_fields )>0){
			      foreach ($this->array_fields as $obj)
			      {
				  
				  $function  = "plgfieldsattachment_".$obj."::construct1();";
				  
				  //$base = JPATH_BASE;
				  //$base = str_replace("/administrator", "", $base);
				  $base =  JPATH_SITE;
				  $file = $base.'/plugins/fieldsattachment/'.$obj.'/'.$obj.'.php';
				  
				  if( JFile::exists($file)){
				      //file exist
				      eval($function);
				      $function  =  'plgfieldsattachment_'.$obj."::getName();";

				      eval("\$plugin_name =".  $function."");
				      //$str .= $field->type." == ".$plugin_name."<br />";
				      eval($function);


				      JError::raiseNotice(500, "sssssdsf");
					      
				      if ($field->type ==  $plugin_name ) {
					     if($backendcategory){ 
						 $value = JRequest::getVar("field_".$field->id, fieldsattachHelper::getfieldsvalueCategories(  $field->id, $id), 'post', 'string', JREQUEST_ALLOWHTML);
						 JError::raiseNotice(500, "sssssdsf");
						  //$value = JRequest::getVar("field_".$field->id, $this->getfieldsvalueCategories(  $field->id, $id));

					     }else{
						 // $value = JRequest::getVar("field_".$field->id,fieldsattachHelper::getfieldsvalue(  $field->id, $id), 'post', 'string', JREQUEST_ALLOWHTML);
						 $value ="";
						 if(isset($_POST["field_".$field->id]))
						      $value = $_POST["field_".$field->id]; 
						  else {
						      $value = fieldsattachHelper::getfieldsvalue(  $field->id, $id);
						  }
						  //$value = JRequest::getVar("field_".$field->id, $this->getfieldsvalue(  $field->id, $id));
					     }
					     $value = addslashes($value);
											 
					      //SESSION VALUE
					      $session = JFactory::getSession(); 
					      $value_session = $session->get("field_".$field->id);  


					      if(!empty($value_session)&& (empty($value)) ) $value =$value_session;

					      //DELETE SESSION VALUE
					      $session->clear("field_".$field->id);
					      
					      
											 
					     $function  =  'plgfieldsattachment_'.$obj.'::renderInput('.$id.','.$field->id.',"'.$value.'","'.$field->extras.'");';
					     //$function  =  "plgfieldsattachment_".$obj."::renderInput(".$id.",".$field->id.",'".$value."','".$field->extras."');";
					     
						 
					    
						 eval("\$tmp=".  $function."");
						  
						 $str .= '<div class="control-group"><label class="control-label" for="field_'.$field->id.'">' . $field->title; 
						 if($field->required) {$str .= '  <span>(*)</span>';}
                                                 $str .= '</label>';
						 $str .= '<div class="controls">'.$tmp.  '</div>';
						 $str .= '</div>';
						 //Reset field of category description =====================
						// fieldsattachHelper::resetToDescription($id, $field->id, &$body);
						$this->resetToDescription($id, $field->id);
					     }
					  

				  }
			   }
		       }
		       
				   
		      //$str .= '</div>';
				  
			
		      //END inputs RENDER =========================================================
		  }
	       //END INPUT ============================================
		}
            }
            
            $this->menuTabstr = $menuTabstr;
            $this->str = $str;
        }
        
      /**
	* Get plugins fieldsattach
	*
	* @access	public
	* @since	1.5
	*/ 
    static public function get_extensions()
    {
        $array_fields  = array();
        $db = JFactory::getDBO(  );
        $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
        $db->setQuery( $query );

        $results = $db->loadObjectList();
        foreach ($results as $obj)
        {
            $array_fields[count($array_fields)] = $obj->element;
        }
        return $array_fields;
    }

         /**
	* Get value of one field content
	*
	* @access	public
	* @since	1.5
	*/
        static public function getfieldsvalue($fieldsid, $articleid)
        {
            $result ="";
            $db	= JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }

         /**
	* Get value of one field category
	*
	* @access	public
	* @since	1.5
	*/
        public function getfieldsvalueCategories($fieldsid, $catid)
        {
            $result ="";
            $db	=  JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_categories_values as a WHERE a.fieldsid='. $fieldsid.' AND a.catid='.$catid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }

         /**
	* Insert fields in categori description
	*
	* @access	public
	* @since	1.5
	*/
       // public function resetToDescription($id, $fieldsid, & $body)
        public function resetToDescription($id, $fieldsid)
        {
            //echo "resetToDescriptionresetToDescriptionresetToDescriptionresetToDescription: ".$fieldsid;
            //$patron ="/{\d+}/i";
            $patron = "{fieldsattach_".$fieldsid."}";
            
            $sustitucion="";
            $this->body = str_replace($patron, $sustitucion, $this->body);

            $this->body = str_replace("<p></p>", "", $this->body);

           // return $cadena ;
        }
        
        /**
	* Get a Title of content
	*
	* @access	public
	* @since	1.5
	*/
        function getFieldsTitle($id)
        {
		$str="";
		if(!empty($id)){
			$db	= & JFactory::getDBO();
			$query = 'SELECT title FROM #__fieldsattach as a WHERE a.id='.$id   ;
			//echo $query."<br>";
			$db->setQuery( $query );
			$tmp = $db->loadResult();
			$str ="";
			if(!empty($tmp))  $str =$tmp;
		}
		return $str;
        }


         /**
	* Get value of one field content
	*
	* @access	public
	* @since	1.5
	*/
        /*function getfieldsvalue($fieldsid, $articleid)
        {
            $result ="";
            $db	= & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }*/


        

}
