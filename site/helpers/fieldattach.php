<?php
/**
 * @version		$Id: fieldattach.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/prgetYoutubeVideooject/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die;


 // require helper file
JLoader::register('fieldsattachHelper',  JPATH_INSTALLATION.DS.'..'.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');


class fieldattach
{
  /**
   * Return the value and Title in a json
   *
   * @param $id  id of article
            *  $fieldsids  id of field
   *
   * @return  json with value, title and published  field.
   * @since 1.6
   */

  static public function getFieldValues($articleid, $fieldsids, $category  = false)
  {
      $db = JFactory::getDBO(  );
      /*if(!$category){
                $query = 'SELECT  b.*  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;
      }else{
                $query = 'SELECT  b.*  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;
      }*/
      $query = 'SELECT  b.title , a.value, b.published ,  b.showtitle FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;

      if($category)  $query = 'SELECT  b.title , a.value, b.published, b.showtitle   FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;



      //echo $query."<br>";
      $db->setQuery( $query );
      
      $result = $db->loadObject();

      //$result->value = base64_encode( $result->value );

      //var_dump($result) ;

      return json_encode($result);
  }

   /**
	 * Return the value of field
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
	static public function getName($articleid, $fieldsids, $category  = false)
	{
	    $db = JFactory::getDBO(  );
      if(!$category){
                $query = 'SELECT  b.title  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;
	    }else{
                $query = 'SELECT  b.title  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;
	    }
	    //echo $query."<br>";
      $db->setQuery( $query );
      $result = $db->loadResult();
      $str = "";
      if(!empty($result)) $str = $result;
	    return $str;
	}
  /**
	 * Return the value of field
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
	static  public function getValue($articleid, $fieldsids, $category = false )
	{
	    $db = JFactory::getDBO(  );

	    $query = 'SELECT  a.value  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;

            if($category)  $query = 'SELECT  a.value  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;

           //echo "<br/>  ".$query."<br/>ss: ".$category."<br>";
            $db->setQuery( $query );
	    $result = $db->loadResult();
            $result = htmlspecialchars_decode($result);
            $str = "";
            if(!empty($result)) $str = $result;
            //echo "VALOR: ".$str."<br/>";
	    return $str;
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
        static public function getValueSelect( $fieldsids, $valor,  $category = false )
	
	{
            //$valor = fieldattach::getValue($articleid, $fieldsids, $category );
            $valortmp = explode(",", $valor);
            
	           $db = JFactory::getDBO(  );

	           $query = 'SELECT  a.extras  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
 
            //echo "<br/>  ".$query."<br/>";
            $db->setQuery( $query );
	          $extras = $db->loadResult(); 
            $str = "";
            $tmp  = array();
            if(!empty($extras)) {
                   
                   $lineas = explode(chr(13),  $extras); 
                     foreach($lineas as $linea){  
                        $linea = explode("|",  $linea);
                        $value = $linea[0];
                        if(count($linea)>1){$value = $linea[1];} 
                        
                        foreach($valortmp as $valor) {
                            //echo "<br> ".trim($value)." -- ".trim($valor);
                            if(strcasecmp ( trim($value) , trim($valor) ) == 0){
                               // echo "AAA";
                                $tmp[count($tmp)] = $linea[0];
                            
                        }
                            //break;
                    }
                }
            }
            
           // echo "VALOR: ".count($tmp)."<br/>";
	    return implode(",",$tmp);
	}



         /**
	 * Return a input HTML tag
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of input
	 * @since	1.6
	 */
	public function getInput($id, $fieldsids, $category  = false)
        {
          $html ='';
          $valor = fieldattach::getValue( $id,  $fieldsids , $category   );
          $title = fieldattach::getName( $id,  $fieldsids , $category  );

          if(!empty($valor))
          {
              $html .= '<div id="cel_'.$fieldsids.'" class=" ">';
              if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<span class="title">'.$title.' </span>';
              $html .= '<span class="value">'.$valor.'</span></div>';
          }
          return $html;
        }

        /**
	 * Return a image HTML tag
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of image
	 * @since	1.6
	 */
	public function getImg($id, $fieldsids, $title=null, $category =false )
	{
	    $html  = "" ;

            $directorio = 'documents' ;
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.value  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*") AND a.articleid= '.$id;
            if($category) {
                 $query = 'SELECT  a.value  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*") AND a.catid= '.$id;
                $directorio = 'documentscategories' ;

            }

            $db->setQuery( $query );
	    $result = $db->loadResult();
            $file="";

            if(!empty($result)) {
                $file = $result;
                if (JFile::exists( JPATH_SITE .DS."images".DS.$directorio.DS. $id .DS. $file)  )
                {
                    $html =  '<img src="images/'.$directorio.'/'.$id.'/'.$result.'" title = "'.$title.'" alt="'.$title.'" />' ;
                }else{
                    if (JFile::exists( JPATH_SITE .DS.$result)  ){
                        $html =  '<img src="'.$result.'" title = "'.$title.'" alt="'.$title.'" />' ;
                    }
                }
            }
	    return $html;
	}
         /**
	 * Return a html of   select
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of  select
	 * @since	1.6
	 */
	public function getSelect($articleid, $fieldsids, $category =false)
        {
              
              $valor = fieldattach::getValue( $articleid,  $fieldsids, $category  );
              $title = fieldattach::getName( $articleid,  $fieldsids , $category );
              $html="";
               
              
              if(!empty($valor))
              {
                  $valorselects = fieldattach::getValueSelect( $fieldsids , $valor );
                  $html .= '<div id="cel_'.$fieldsids.'" class=" ">';
                  if(fieldattach::getShowTitle(   $fieldsids  )) $html .= '<span class="title">'.$title.' </span>';
                  $html .= '<span class="value">'.$valorselects.'</span></div>';
              }
              return $html;
        }


        /**
	 * Return the value of field
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
	/*public function getValueSelect( $fieldsids, $valor , $category  = false )
	{

	    $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.extras  FROM #__fieldsattach as a   WHERE a.id  = '.$fieldsids.'    ';
	    //echo $query."<br/>";
            $db->setQuery( $query );
	    $result = $db->loadResult();
            $tmp = '';$str = "" ;
            if(!empty($result)) $tmp = $result;
              //echo  "<br/>extras: ".$tmp;
            $lineas = explode(chr(13),  $tmp);
            if(count($lineas)>0)
            {
                foreach ($lineas as $linea)
                {
                   // echo  "<br/>".$linea. "->".$valor;
                    $pos = strrpos($linea,  $valor);
                    if ($pos === false) {

                    }else{
                        $tmp = explode('|',  $linea);
                        $str = $tmp[0];
                        break;
                        }
                }
            }

	    return $str;
	}*/

      /**
	 * Return a html of   file download
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of  file download
	 * @since	1.6
	 */
	public function getFileDownload($articleid, $fieldsids , $category =false)
        {
            $html =  '';
            
            //GET Extras ***************************
            $extras = fieldattach::getExtra($fieldsids);
           
            if(!empty($extras))
            { 
                if(count($extras)>0) $selectable = $extras[0]; 
            }
             
            //GET Values ***************************
            $valor = fieldattach::getValue( $articleid,  $fieldsids, $category  );
            $title = fieldattach::getName($articleid,  $fieldsids);
            $directorio ="documents";
            
            $tmpfile = explode("|",$valor);
            $file =  $tmpfile[0];
            $titlefile = JText::_("DOWNLOAD");
            if(count($tmpfile)>1){
                $titlefile = $tmpfile[1];
            }

            if($category) {
                   $directorio = 'documentscategories' ;
            }
            
            //Build url link
            if($selectable=="selectable")
            {
                $file_absolute  =  fieldsattachHelper::getabsoluteURL().$file;

            }else{  
                $file_absolute = 'images/'.$directorio.'/'.$articleid.'/'.  $file; 
                 
            } 
            
            
            if(!empty($valor)) {
                $html .= '<div class="download">';
                if($selectable=="selectable"){
                    if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<span class="title">'.$title.' </span>';
                    $html .=  '<a href="'.$file_absolute.'"   alt="'.$titlefile.'" class="downloads" target="_blank" />'.$titlefile.'</a>';
                  
                }else{
                    if (JFile::exists( JPATH_SITE .DS."images".DS.$directorio.DS. $articleid .DS. $file)  )
                    {
                        if(fieldattach::getShowTitle(   $fieldsids  ))  $html .= '<span class="title">'.$title.' </span>';
                            $html .=  '<a href="'.$file_absolute.'"   alt="'.$titlefile.'" class="downloads" target="_blank" />'.$titlefile.'</a>';
                    }
                }
                $html .= '</div>';
            }
	    return $html;
        }

         /**
	 * Return a html of multiple select
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of multiple select
	 * @since	1.6
	 */
	public function getSelectmultiple($articleid, $fieldsids , $category =false)
        {
              $html ='';
              $valor = fieldattach::getValue( $articleid,  $fieldsids , $category );
              if(!empty($valor))
              {
                    $html .= '<div id="cel_'.$field->id.'" class="'.$field->type.'">';
                    if(fieldattach::getShowTitle(   $fieldsids  )) $str .= '<span class="title">'.$field->title.' </span>';
                    $tmp = explode(",",$valor);
                    $conta = 0;
                    foreach($tmp as $obj)
                    {
                        $conta++;
                        if(!empty($obj)) $html .= '<span class="value num_'.$conta.'">'.$obj.'</span>';

                    }
                    $html .= '</div>';

              }
              return $html;
        }
        
        /**
	 * Return a true or false
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html gallery   list
	 * @since	1.6
	 */
        public function GalleryExist($articleid, $fieldsids, $category = false)
        {
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.articleid= '.$articleid.' ORDER BY a.ordering';
            if($category)
            {
                $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.catid= '.$articleid.' ORDER BY a.ordering';

            }
            
            $db->setQuery( $query );
	    $result = $db->loadObjectList();
            if(count($result)>0)
            {
                return true;
            }else
            {
                return false;
            }
        }
        /**
	 * Return a image gallery HTML tag
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html gallery   list
	 * @since	1.6
	 */
	public function getImageGallery($articleid, $fieldsids, $category = false)
	{
	    $html =  '<ul class="gallery">';
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.articleid= '.$articleid.' ORDER BY a.ordering';
            if($category)
            {
                $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.catid= '.$articleid.' ORDER BY a.ordering';

            }
            $db->setQuery( $query );
	    $result = $db->loadObjectList();
            $firs_link = '';
            $cont = 0;

            $sitepath  =  fieldsattachHelper::getabsoluteURL();

            if(!empty($result)){
                foreach ($result as $obj){
                    //if (JFile::exists( JPATH_SITE .DS."images".DS."documents".DS. $articleid .DS. $result->value)  )
                    $html .=  '<li>' ;
                    if (JFile::exists( JPATH_SITE .DS. $obj->image2)  )
                    {
                        $html .=  '<a href="'.$sitepath.''.$obj->image1.'" id="imgFiche" class="nyroModal" title="'.$obj->title.'" rel="gal_'.$articleid.'">';
                        $html .=  '<img src="'.$sitepath.''.$obj->image2.'"  alt="'.$obj->title.'" />';
                    }else{$html .=  '<img src="'.$sitepath.''.$obj->image1.'"  alt="'.$obj->title.'" />';}

                    if (JFile::exists( JPATH_SITE .DS. $obj->image2)  )
                    {
                        $html .=  '</a>';
                    }
                    $html .=  '</li>';
                    $cont++;
                }
            }
            $html .=  '</ul>';

	    return $html;
	}

        /**
	 * Return a image HTML tag
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of image
	 * @since	1.6
	 */
	public function getFirstImageGallery($articleid, $fieldsids, $title=null, $category = false)
	{
	    $html =  '<div class="gallery">';
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.articleid= '.$articleid;
            if($category)  $query = 'SELECT  a.* FROM #__fieldsattach_images as a  WHERE a.fieldsattachid = '.$fieldsids.' AND a.catid= '.$articleid;

            $db->setQuery( $query );
	    $result = $db->loadObjectList();
            $firs_link = '';
            $cont = 0;
            if(!empty($result)){
                foreach ($result as $obj){

                    $html .=  '<a href="'.JURI::base().''.$obj->image1.'" id="imgFiche" class="nyroModal" title="'.$obj->title.'" rel="gal_'.$articleid.'">';
                    if($cont==0){


                        $html .=  '<img src="'.JURI::base().''.$obj->image1.'"  alt="'.$obj->title.'" />';

                        }
                    else{ $html .= ''; }
                    $html .=  '</a>';

                    if($cont==1){   $firs_link = JURI::base().''.$obj->image1 ;}

                    $cont++;
                }
            }
            $html .=  '</div>';
            if(!empty($firs_link)){
                $html .=  '<div class="vergallery">';
                $html .=  '<a href="'.$firs_link.'" id="imgFiche" class="nyroModal" title="'.$obj->title.'" rel="gal_'.$articleid.'">';
                $html .=  JText::_("Ver Imagenes");
                $html .=  '</a>';
                $html .=  '</div>';
            }
	    return $html;
	}
/**
	 * Return a image HTML tag
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of image
	 * @since	1.6
	 */
	public function getVideoGallery($articleid, $fieldsids)
	{
            $html = '';
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT  a.value  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldsids.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;
	    //echo $query;
            $db->setQuery( $query );
	    $result = $db->loadResult();
	    if(!empty($result))
            {
                $html .=  '<div class="vervideogallery">';
                $html .= '<a href="http://www.youtube.com/watch?v='.$result.'" class="nyroModal"  >'.JText::_("Ver Video").'</a><br />';
                $html .=  '</div>';
            }

            return $html;
        }

        /**
	 * Return a image VIMEO IFRAME
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	vidmeo IFRAME
	 * @since	1.6
	 */
	public function getVimeoVideo($articleid, $fieldsids, $category = false)
	{
            $extrainfo = fieldattach::getExtra($fieldsids);
            $width="300";
            $height="300";

            if((count($extrainfo) >= 1)&&(!empty($extrainfo[0]))) $width= $extrainfo[0];
            if((count($extrainfo) >= 2)&&(!empty($extrainfo[1]))) $height= $extrainfo[1];

            $code = fieldattach::getValue(  $articleid, $fieldsids, $category);
            if(!empty($code)){
                $html  = '<div id="cel_'.$fieldsids.'" class="vimeo">';
                $html .= '<iframe src="http://player.vimeo.com/video/'.$code.'" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';
                $html .= '</div>';
            }
            return $html;
        }

        /**
	 * Return a image YOUTUBE OBJECT
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	object video
	 * @since	1.6
	 */
	public function getYoutubeVideo($articleid, $fieldsids, $category=false)
	{
            $extrainfo = fieldattach::getExtra($fieldsids);
            $width="300";
            $height="300";
            $html="";
            if((count($extrainfo) >= 1)&&(!empty($extrainfo[0]))) $width= $extrainfo[0];
            if((count($extrainfo) >= 2)&&(!empty($extrainfo[1]))) $height= $extrainfo[1];


            $code = fieldattach::getValue(  $articleid,  $fieldsids, $category);
            if(!empty($code)){
             $html .= '<div id="cel_'.$fieldsids.'" class="youtube">';
             $html .=  '<object width="'.$width.'" height="'.$height.'">
               <param name="movie" value="http://www.youtube.com/v/'. $code.'&amp;hl=en_US&amp;fs=1&amp;"></param>
               <param name="allowFullScreen" value="true"></param>
               <param name="wmode" value="transparent"></param>

               <param name="allowscriptaccess" value="always"></param>
               <embed
                  src="http://www.youtube.com/v/'.$code.'&amp;hl=en_US&amp;fs=1&amp;"
                  type="application/x-shockwave-flash"
                  allowscriptaccess="always"
                  allowfullscreen="true"
                  wmode = "transparent"
                  width="'.$width.'"
                  height="'.$height.'">
               </embed>
            </object>
            ';

              $html .= '</div>';
            }
            return $html;
        }
        /**
	 * Return a table HTML with a list of units
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of table
	 * @since	1.6
	 */
  static public function getExtra($fieldsids)
	{
            $db = JFactory::getDBO(  );
	    $query = 'SELECT a.* FROM #__fieldsattach as a  WHERE a.id = '.$fieldsids;


            $db->setQuery( $query );
	    $result  = $db->loadObject();
            $extrainfo = explode("|",$result->extras);
            return $extrainfo;
        }

         /**
	 * Return a table HTML with a list of units
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of table
	 * @since	1.6
	 */
    static public function getShowTitle($fieldsids)
	  {
        $db = JFactory::getDBO(  );
	      $query = 'SELECT a.* FROM #__fieldsattach as a  WHERE a.id = '.$fieldsids; 
        $db->setQuery( $query );
	      $result  = $db->loadObject();

        return $result->showtitle;
    }

  /**
	 * Return a table HTML with a list of units
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
	 *
	 * @return	html of table
	 * @since	1.6
	 */
	public function getListUnits($articleid, $fieldsids, $category  = false)
	{
            $str ='<div>';
            $extrainfo = fieldattach::getExtra($fieldsids);
            $title = fieldattach::getName( $articleid,  $fieldsids , $category  );
            if(fieldattach::getShowTitle(   $fieldsids  ))  $str .= '<div class="title">'.$title.' </div>';
            $str .='<table><thead><tr>';
            foreach ($extrainfo as $result )
                {
                     $str .='<th>'.$result.'</th>';
                }
            $str .='</tr></thead>';
            $valor = fieldattach::getValue($articleid, $fieldsids, $category);
            $valor = str_replace("&quot;",  '"', $valor );
            $json = explode("},", $valor);
            $i = 0;
            foreach ($json as $linea )
            {
                //$linea =  substr($linea, 0 , strlen($linea)-1);

                $linea = str_replace("},", "", $linea);
                $linea = str_replace("}", "", $linea);
                $linea =   $linea. '}';

               // $jsonobj = json_decode('{"Modelo":"asd","Largo_mts":"sdafsfas","Acción":"dfasdf","Tramos":"","Plegado":"","ø_Base":"","Peso_g":"","Cajas":"","CÓDIGO":""}');
                $jsonobj = json_decode( $linea );
                $str .='<tr>';
                foreach ($extrainfo as $obj )
                {
                     if(isset( $jsonobj->{$obj})) $str .='<td>'.  $jsonobj->{$obj} .'</td>';
                }
                $str .='</tr>';
            }


            $str .='</table></div>';

            return $str;
        }

        /**
	 * Create two images for a button.
	 *
	 * @param	$id	 id of article
         *              $fieldsids  id of field
         *              $width  width of resize
         *              $height height of resize
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
        public function creteButtonImage($id, $fieldsids, $width, $height)
        {
            $db = JFactory::getDBO(  );
            $path=  'images'.DS.'documents';

            $query = 'SELECT  a.value  FROM #__fieldsattach_values as a WHERE fieldsid='.$fieldsids.' AND articleid= '.$id;

            $db->setQuery( $query );
            $result = $db->loadObject();

            $ancho = $width;
            $alto = $height;

            $nombre = JPATH_BASE. DS .$path. DS . $id. DS . $result->value;
            $nombre = JPATH_BASE. DS ."images". DS . "documents" . DS . $id . DS .  $result->value;
            $archivo = $path. DS . $id. DS . "btn_1" ;
            $archivo_on = $path. DS . $id. DS . "btn_1_on" ;


           // echo "<br>".$nombre."<br>";
            if (preg_match('/jpg|jpeg|JPG/',$nombre))
                {
                $imagen=imagecreatefromjpeg($nombre);
                $archivo .=".jpg";$archivo_on .=".jpg";
                }
            if (preg_match('/png|PNG/',$nombre))
                {
                $imagen=imagecreatefrompng($nombre);
                 $archivo .=".png";$archivo_on .=".png";

                }
            if (preg_match('/gif|GIF/',$nombre))
                {
                $imagen=imagecreatefromgif($nombre);
                $archivo .=".gif";$archivo_on .=".gif";
                }

            $x=imageSX($imagen);
            $y=imageSY($imagen);
            $w=$ancho;
            $h=$alto;
            if ($x > $y)
            {
            $w=$ancho;
            $h=$y*($alto/$x);
            }

        if ($x < $y)
            {
            $w=$x*($ancho/$y);
            $h=$alto;
            }

        if ($x == $y)
            {
            $w=$ancho;
            $h=$alto;
            }

            //Crear imagen sin filtro
            $destino_on=ImageCreateTrueColor($w,$h);
            imagecopyresampled($destino_on,$imagen,0,0,0,0,$w,$h,$x,$y);

            if(imagefilter($imagen, 1, 50))
            {
                $app = JFactory::getApplication();
                $app->enqueueMessage( JTEXT::_("Apply filter:")  );
            }  else {
                JError::raiseWarning( 100,  JTEXT::_("Apply filter ERROR:").$filter   );
            }

            $destino=ImageCreateTrueColor($w,$h);
           // echo "<br>destini: ".$destino;
            imagecopyresampled($destino,$imagen,0,0,0,0,$w,$h,$x,$y);


            //echo " archivo:: ".$nombre;

            $tmp = JPATH_BASE. DS . $archivo;
            $tmp2 = JPATH_BASE. DS . $archivo_on;

            if (preg_match("/png/",$tmp))
                {
                imagepng($destino,$tmp);
                imagepng($destino_on,$tmp2);
                }
            if (preg_match("/gif/",$tmp))
                {
                imagegif($destino,$tmp);
                imagepng($destino_on,$tmp2);
                }
            else
                {
                imagejpeg($destino,$tmp);
                imagepng($destino_on,$tmp2);
                }

            imagedestroy($destino);
           /* imagedestroy($imagen);*/

            return '<img src='. $archivo.' alt =" " id="imagen_'.$id.'"/>';
        }
        
        
        static public function isRequired($fieldsids)
        {
            
            $db = JFactory::getDBO(  );
	    $query = 'SELECT a.required FROM #__fieldsattach as a  WHERE a.id = '.$fieldsids;


            $db->setQuery( $query );
	    $result  = $db->loadResult();

            return $result;
        }
        
        
        /**
	 * get a GOOGLE MAPS
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field 
         *              $category if category
	 *
	 * @return	value to field.
	 * @since	1.6
	 */
        
        function getGoogleMaps($articleid, $fieldsids, $category = false)
        {
            
            $value = fieldattach::getValue($articleid, $fieldsids,$category);
            
            
            $db = JFactory::getDBO(  );

	    $query = 'SELECT  a.extras  FROM #__fieldsattach  as a WHERE a.id = '.$fieldsids;
  
            $db->setQuery( $query );
	    $extras = $db->loadResult(); 
            $str = "";
            $tmp  = array();
            
            $width = 300;
            $height = 300;
            if(!empty($extras)) {
                   
                   $lineas = explode("|",  $extras); 
                   
                   $width= $lineas[0];
                   if(count($lineas)>1){$height = $lineas[1];} 
 
            } 
            
            JLoader::register('plgfieldsattachment_googlemap',  JPATH_INSTALLATION.DS.'..'.DS.'plugin/fieldsattachment/googlemap/googlemap.php');
            
            
            echo plgfieldsattachment_googlemap::getHTML($articleid, $fieldsids);
            
        }
        
        
        
         /**
	 * get a LINK
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field 
         *              $category if category
	 *
	 * @return	value to link.
	 * @since	1.6
	 */
        function getLink($articleid, $fieldid)
        { 
            
              $html ='';
              $valor = fieldattach::getValue( $articleid,  $fieldid  );
              $title = fieldattach::getName( $articleid,  $fieldid  );
              
              $tmp = explode('|',  $valor);
              $url = $tmp[0]; 
              if(count($tmp)>1){
                  if(!empty($tmp[1])) $text = $tmp[1];
                  else $text = $url;
              }
              else $text = $url;
             
              
              
              if(!empty($url))
              {
                    $pos = strrpos($url, "http://");
                    if ($pos === false) { // note: three equal signs
                       $valorhtml = '<a href="'.$url.'" >';
                    }else{
                        $valorhtml = '<a href="'.$url.'" target="_blank">';
                    }

                    $valorhtml .= $text.'</a>';
            
                    $html .= '<div id="cel_'.$fieldid.'" class="link">';
                    if(fieldattach::getShowTitle(   $fieldid  ))  $html .= '<span class="title">'.$title.' </span>';
                    $html .= '<span class="value">'.$valorhtml.'</span></div>';
              }
              return $html; 
        }
         
        
         /**
	 * get a GENERIC FUNCTION MORE EASY FOR USER!!!
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field 
         *              $category if category
	 *
	 * @return	value to link.
	 * @since	1.6
	 */
        static function getFieldValue($articleid, $fieldid, $category  = false, $write = true)
        { 
            global $globalreturn ;
            
            $db = JFactory::getDBO(  );
            if(!$category){
                $query = 'SELECT  b.type, c.access  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid INNER JOIN  #__fieldsattach_groups as c ON  b.groupid = c.id WHERE b.published= true AND  a.fieldsid IN ('.$fieldid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;
      	    }else{
                $query = 'SELECT  b.type, c.access  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid INNER JOIN  #__fieldsattach_groups as c ON  b.groupid = c.id  WHERE b.published= true AND a.fieldsid IN ('.$fieldid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;
      	    } 
            
            $db->setQuery( $query );
            $record = $db->loadObject();
            $str    = ""; 
             
            //User access view the layout takes some responsibility for display of limited information.
            $user = JFactory::getUser();
            $groups = $user->getAuthorisedViewLevels();  

            if( in_array($record->access, $groups) ) 
            { 
                $type= $record->type;

                JPluginHelper::importPlugin('fieldsattachment'); // very important

    	          if(empty($category)) $category = 0;
                 
                $function  = "plgfieldsattachment_".$type."::getHTML( ".$articleid.", ".$fieldid.", ".$category." );";
                
                $base = JPATH_SITE; 

                $file = $base.'/plugins/fieldsattachment/'.$type.'/'.$type.'.php';  
                
                $html=""; 
                
                if( JFile::exists($file)){
                  
                    //file exist 
                    eval($function);
                }
                  
                 if($write)
                  echo $globalreturn ; 
                else
                  return $globalreturn; 

            }       
	}
        
        
         /**
	 * get a GENERIC FUNCTION MORE EASY FOR USER!!!
	 *
	 * @param	$articleid	 id of article
         *              $fieldsids  id of field 
         *              $category if category
	 *
	 * @return	value to link.
	 * @since	1.6
	 */
        function getCategoryFieldValue($articleid, $fieldid, $category  = false)
        { 
            
            $db = &JFactory::getDBO(  );
            if(!$category){
                $query = 'SELECT  b.type  FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.articleid= '.$articleid;
	    }else{
                $query = 'SELECT  b.type  FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON  b.id = a.fieldsid  WHERE a.fieldsid IN ('.$fieldid.') AND (b.language="'. JRequest::getVar("language", "*").'" OR b.language="*" ) AND a.catid= '.$articleid;
	    } 
             
            
            $db->setQuery( $query );
	    $type = $db->loadResult();
            $str = "";
              
              
              
            JPluginHelper::importPlugin('fieldsattachment'); // very important
                    //select

            //$this->array_fields = fieldsattachHelper::get_extensions() ;
 
            $function  = "plgfieldsattachment_".$type."::getHTML( $articleid, $fieldid, true, true );";
            $base = JPATH_BASE;
            $base = str_replace("/administrator", "", $base);
            $base = JPATH_SITE;
            $file = $base.'/plugins/fieldsattachment/'.$type.'/'.$type.'.php'; 
            
            $html=""; 
            
            if( JFile::exists($file)){
                //file exist
                $html = eval($function);
            }
              
            return $html; 
        }

}
