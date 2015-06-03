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
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
JLoader::register('fieldattach',  $sitepath.'components/com_fieldsattach/helpers/fieldattach.php'); 
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');

include_once $sitepath.'/administrator/components/com_fieldsattach/helpers/extrafield.php';
 

class plgfieldsattachment_imagegallery extends extrafield
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
         
	static function construct1( )
	{ 
	    parent::getLanguage(plgfieldsattachment_imagegallery::getName());  
             
	}

    static public function getName()
    {  

          return "imagegallery";
             // return  $this->name;
    }
	  
        
    static function renderInput($articleid, $fieldsid, $value, $extras=null )
    {
        $directory = "";
        if ( JFactory::getApplication()->isAdmin()) {
            $directory = "";
        }
    
    	$session = JFactory::getSession();
    	$session->set('articleid',$articleid);
		$session->set('fieldsattachid',$fieldsid);
		
        $sitepath  =  fieldsattachHelper::getabsoluteURL();
        $str_gallery = '<div id="gallery_'.$fieldsid.'" class="galleryfield">'.plgfieldsattachment_imagegallery::getGallery1($articleid, $fieldsid).'</div>';
                $str =''; 
                $str .= $str_gallery; 
		
                $str .= "<script type=\"text/javascript\">
                                    
					window.addEvent('domready', function(){
							   
							  update_gallery".$fieldsid."(); 
							   
								 
					}); 
                                           
					
					function update_gallery".$fieldsid."()
					{
                                                    
						 
					  	var url_".$fieldsid." = \"".JURI::base(false)."/index.php?option=com_fieldsattach&view=fieldsattachimagesajax&tmpl=component&catid=".$articleid."&fieldsid=".$fieldsid."\";
					 	 
                                                    var xmlhttp;
                                                    if (window.XMLHttpRequest)
                                                    {// code for IE7+, Firefox, Chrome, Opera, Safari
                                                    xmlhttp=new XMLHttpRequest();
                                                    }
                                                    else
                                                    {// code for IE6, IE5
                                                    xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
                                                    }
                                                    xmlhttp.onreadystatechange=function()
                                                    {
                                                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                                        {
								document.getElementById(\"gallery_".$fieldsid."\").innerHTML=xmlhttp.responseText;
								SqueezeBox.initialize({});
								SqueezeBox.assign($$('#gallery_".$fieldsid." a.modal'), { parse: 'rel'});
								
								//Sortable ********************************* 
								jQuery( '#gallerysortable".$fieldsid."' ).sortable(
										  {
										  update:  function (event, ui) {
											  //Update the position
											  update_order(".$fieldsid.");
										  }});
								jQuery( '#gallerysortable".$fieldsid."' ).disableSelection();
                                                  
                                                        }
                                                    }
                                                    xmlhttp.open(\"GET\",  url_".$fieldsid." ,true);
                                                    xmlhttp.send(); 
					}
					
					function update_order(fieldsattachid)
					{
					      //Get order *****
					      var tmparray  = new Array();
					      var cont = 0;
					      jQuery( '#gallerysortable'+fieldsattachid+' li' ).each
					      ( 
						       function(){
								 
								var id = jQuery(this).attr('id');
								var  tmp = String(id).split('_'); 
								tmparray[cont] = tmp[2];
								cont++;
						       }
					      );
					      
					      //AJAX CALL *****
					      var url  = \"".JURI::root(false)."/administrator/index.php?option=com_fieldsattach&task=fieldsattachimagesorderajax&catid=".$articleid."&fieldsid=\"+fieldsattachid+\"&order=\"+tmparray.toString();
					       
                                                  var xmlhttp;
					      if (window.XMLHttpRequest)
					      {// code for IE7+, Firefox, Chrome, Opera, Safari
					      xmlhttp=new XMLHttpRequest();
					      }
					      else
					      {// code for IE6, IE5
					      xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
					      }
					      xmlhttp.onreadystatechange=function()
					      {
					      if (xmlhttp.readyState==4 && xmlhttp.status==200)
						  {
							     // alert(xmlhttp.responseText);
						  }
					      }
					      xmlhttp.open(\"GET\",  url ,true);
					      xmlhttp.send(); 
					      
					}
					
			</script>";
        return  $str  ;
    }
 
    static function getHTML($articleid, $fieldsid, $category = false, $write=false)
    {
        //$str =   fieldattach::getImageGallery($articleid, $fieldsid,$category);
        global $globalreturn;
        $html ="";
        //$html =  '<ul class="gallery">';
        $db = JFactory::getDBO(  );
    $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsid.' AND a.articleid= '.$articleid.' ORDER BY a.ordering';
        if($category)
        {
            $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsid.' AND a.catid= '.$articleid.' ORDER BY a.ordering';

        }
        $db->setQuery( $query );
    $result = $db->loadObjectList();
        $firs_link = '';
        $cont = 0;

        $sitepath  =  fieldsattachHelper::getabsoluteURL();
        $title = fieldattach::getName( $articleid,  $fieldsid , $category  );
        $published = plgfieldsattachment_imagegallery::getPublished( $fieldsid  );

        if(!empty($result) && $published){
            $html = plgfieldsattachment_imagegallery::getTemplate($fieldsid, "imagegallery");
            $line = plgfieldsattachment_imagegallery::getLineTemplate($fieldsid);
            $lines = "";
            
            /*
            Templating IMAGEGLLERY *****************************
           
            [URL1] - Image1
            [URL2] - Image2
            [URL3] - Image3
            [URL1] - Image1
            [FIELD_ID] - Field id 
            [ARTICLE_ID] - Article id
            [TITLE] - Article id
            [DESCRIPTION] - Article id
           
            */ 

            
            foreach ($result as $obj){
                $img1 = $sitepath.''.$obj->image1;
                $img2 = $sitepath.''.$obj->image2;
                $img3 = $sitepath.''.$obj->image3;
                
                $title = $obj->title;
                $description = $obj->description;
                
                $tmp = $line;
                
                $tmp = str_replace("[URL1]", $img1, $tmp);
                $tmp = str_replace("[URL2]", $img2, $tmp);
                $tmp = str_replace("[URL3]", $img3, $tmp);
                $tmp = str_replace("[ARTICLE_ID]", $articleid, $tmp);
                $tmp = str_replace("[FIELD_ID]", $fieldsid, $tmp);
                $tmp = str_replace("[TITLE]", $title, $tmp);
                $tmp = str_replace("[DESCRIPTION]", $description, $tmp);
                
                /*
                if (!JFile::exists( JPATH_SITE .DS. $obj->image2)  )
                {
                    $tmp = str_replace('</a>', '', $tmp);
                    $tmp = preg_replace('/<a[^>]+href[^>]+>/', '', $tmp);
                }
                */
               
                $lines.= $tmp;
            }
            
            
            
            if(fieldattach::getShowTitle(   $fieldsid  )) $html = str_replace("[TITLE]", $title, $html); 
            else $html = str_replace("[TITLE]", "", $html); 

            $html = str_replace("[ARTICLE_ID]", $articleid, $html);
            $html = str_replace("[FIELD_ID]", $fieldsid, $html);
            $html = str_replace("[LINES]", $lines, $html);
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

    function action( $articleid, $fieldsid, $fieldsvalueid )
    {

    }
        
    static function getGallery1($articleid, $fieldsattachid)
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

        $db->setQuery($query);
	 //$str = $query;
        $rows= $db->loadObjectList();
        $str = '<div class="page-header" style="position:relative;"><a class=\'modal\' rel=\'{handler: "iframe", size: {x: 980, y: 500}}\' href=\''.JURI::base(false).'/'.$directory.'index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2\'></a>';
        
        $str .= '<a class="modal btn btn-primary" rel="{handler: \'iframe\', size: {x: 980, y: 500}}" href="'.JURI::base(false).'index.php?option=com_fieldsattach&view=fieldsattachimage&layout=edit&tmpl=component&reset=2&fieldsattachid='.$fieldsattachid.'">'.JText::_('NEW IMAGE').'</a>';
        $str .= '&nbsp;&nbsp;';
        
        $str .= '<a href="#" onclick="update_gallery'.$fieldsattachid.'();return false;"><img src="'.JURI::base(false).'/'.$directory.'components/com_fieldsattach/images/icon-refresh.png" alt="refresh" /></a>';
        
        $str .= '</div>'; 
    $str .= '
	  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" /> 
	  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>';
    $str .= " 
<style> 
.ui-sortable{overflow:hidden; margin:0; padding:0;}
.sortable li  {
cursor: move;margin: 0 10px 12px 0; border:1px #ccc solid; float:left;border-radius: 5px;
padding: 3px;   width:190px; height:190px; overflow:hidden;
} 
</style>"; 
        $str .= '<div   class="ui-sortable"><ul id="gallerysortable'.$fieldsattachid.'" class="sortable" >';
        
            $sitepath  =  fieldsattachHelper::getabsoluteURL(); 
            if($rows>0){
               foreach ($rows as $row)
                {
                  $url_edit =JURI::base(false).'/index.php?option=com_fieldsattach&view=fieldsattachimage&tmpl=component&layout=edit&id='.$row->id.'&fieldsattachid='.$fieldsattachid.'&reset=2';
                  $url_delete =JURI::base(false).'/index.php?option=com_fieldsattach&amp;view=fieldsattachimages&amp;task=delete&amp;id='.$row->id.'&amp;tmpl=component&amp;fieldsid='.$fieldsattachid;
                  $str.= '
                  <li id="image_'.$fieldsattachid.'_'.$row->id.'" >
			    <div class="btn-group" style="margin-bottom:4px;">
				       <a class="modal btn btn-mini" href="'.$sitepath.$row->image1.'">'.JText::_('ZOOM').'</a>
				       <a class="modal btn btn-mini" href="'.$url_edit.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}">'.JText::_('EDIT').'</a>
				       <a class="modal btn btn-mini" href="'.$url_delete.'" rel="{handler: \'iframe\', size: {x: 980, y: 500}}">'.JText::_('DELETE').'</a>
				 
			    </div>
			    <img src="'.$sitepath.$row->image1.'" alt="'.$row->title.'" />
	               	   
                  </li>
                  ';

                }
            }
            
        $str .= '</ul></div>
';
        
        return $str;
    }
         
        
    /**
	 * getTemplate
	 *
	 * @access	public 
	 * @return  	html of field
	 * @since	1.0
	 */
    static function getLineTemplate($fieldsids)
    {
         //Search field template GENERIC *****************************************************************
          $templateDir =  dirname(__FILE__).'/tmpl/imagegallery_line.tpl.php'; 
          $html = file_get_contents ($templateDir);
          
          //Search field template in joomla Template  ******************************************************  
          $app = JFactory::getApplication();
          $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/imagegallery_line.tpl.php';
          
          if(file_exists($templateDir))
          {
               
              $html = file_get_contents ($templateDir);
          }
          
          //Search a specific field template in joomla Template  *********************************************  
          $app = JFactory::getApplication();
          $templateDir =  JPATH_BASE . '/templates/' . $app->getTemplate().'/html/com_fieldsattach/fields/'.$fieldsids.'_imagegallery_line.tpl.php';
          
          if(file_exists($templateDir))
          { 
              $html = file_get_contents ($templateDir);
          }
          
          return $html;
    }
 
       

         

}