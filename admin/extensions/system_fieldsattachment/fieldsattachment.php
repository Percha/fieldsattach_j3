<?php
/**
 * @version     $Id: fieldsattachement.php 15 2011-09-02 18:37:15Z cristian $
 * @package     fieldsattach
 * @subpackage      Components
 * @copyright       Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author      Cristian Grañó
 * @link        http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license     License GNU General Public License version 2 or later
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport('joomla.filesystem.folder');


//this is intializer.php
defined('DS')?  null :define('DS',DIRECTORY_SEPARATOR);

 
// require helper file
/*$dir = dirname(__FILE__);
$dir = $dir.DS.'..'.DS.'..'.DS.'..'.DS;
JLoader::register('fieldsattachHelper',   $dir.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');*/

// require helper file
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath); 
$sitepath = JPATH_SITE ;
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');



class plgSystemfieldsattachment extends JPlugin
{
    private $str ;
    private $path;
    public $array_fields  = array();
    public $fields  = array();
    
     
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @access  protected
     * @param   object  $subject The object to observe
     * @param   array   $config  An array that holds the plugin configuration
     * @since   1.0
     */
    function plgSystemfieldsattachment( $subject, $config)
    {
             
             
        //DELETE ROWS =======================================================
        $option = JRequest::getCmd('option', '');
        $view = JRequest::getCmd('view', 'default');  
        if ( JFactory::getApplication()->isAdmin()) {
            if (($option == 'com_content' )&&($view == "articles")) { $this->cleandatabase(); } 
        }
               
               
        parent::__construct($subject, $config);
                
                $this->path= '..'.DS.'images'.DS.'documents';
                if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit" && JRequest::getVar('extension')=="com_content"  )){
                     $this->path= '..'.DS.'images'.DS.'documentscategories';
                }
                
        $mainframe = JFactory::getApplication();

        //ADMIN *****
        if ($mainframe->isAdmin()) 
        {
            $document = JFactory::getDocument();
                    
            $document->addStyleSheet(   JURI::base().'../plugins/system/fieldsattachment/js/style.css' );
            $dispatcher = JDispatcher::getInstance();
    
            JPluginHelper::importPlugin('fieldsattachment'); // very important
             
            //GET TYPES IN PLUGIN FOLDER
            $this->array_fields = fieldsattachHelper::get_extensions() ;
    
             foreach ($this->array_fields as $obj)
            {
                $function  = "plgfieldsattachment_".$obj."::construct1();";
                $base = JPATH_BASE;
                $base = str_replace("/administrator", "", $base);
                $base = JPATH_SITE;
                $file = $base.'/plugins/fieldsattachment/'.$obj.'/'.$obj.'.php';
                
                /**
                Cristian - 15/07/2015
                */
                if( JFile::exists($file)){
                    //file exist
                    if(method_exists("plgfieldsattachment_".$obj, "construct1")){
                         eval($function);
                     }else{
                         
 
                        // Add a message to the message queue
                        JError::raiseWarning( 100, 'Fieldsattach : Update a plugin '.$obj );
                         
                     }
                   
                }
                        
            }
                    
            //DELETE
            //JError::raiseWarning( 100,  "DELETE".JRequest::getVar("cid")   ." task:".JRequest::getVar("task")  );
            if(JRequest::getVar("task") == "articles.trash") { $this->deleteFields();}
            return;
        } 

 
    }   
        
    /**
    * Function for batch FUCTION
    *
    * @access   public
    * @since    1.5
    */
        
    public function batchcopy($newId, $oldId)
    {
        $db = & JFactory::getDBO();
        $query = 'SELECT a.* FROM #__content as a  WHERE  a.id ='. $newId ; 
        $db->setQuery($query);  


        $article = $db->loadObject();  
        plgSystemfieldsattachment::copyArticle($article, $oldId); 
    }
       
       

         
    /**
    * Function DELETE Fields
    *
    * @access   public
    * @since    1.5
    */
        
    public function deleteFields()
    {
        $app = JFactory::getApplication();
        $db =  JFactory::getDBO();
        $arrays = JRequest::getVar("cid");
        $ids = "";
        foreach ($arrays as $obj)
        { 
            $query = 'DELETE FROM  #__fieldsattach_values WHERE articleid= '.$obj ;
            $db->setQuery($query);
            $db->query();
            $app->enqueueMessage( JTEXT::_("Delete fields of ID ") . $obj )   ;

        } 

    }
        
    /**
    * Function DELETE Fields
    *
    * @access   public
    * @since    1.5
    */
    public function onContentBeforeDelete($context,  $article, $isNew)
    {

    }
     
    /**
    * Function onContentBeforeSave
    *
    * @access   public
    * @since    1.5
    */

    public function onContentBeforeSave($context, $article, $isNew)
    { 
              
        //***********************************************************************************************
        //create array of fields  ****************  ****************  ****************
        //***********************************************************************************************
        //CATEGORIES ==============================================================
        $user   = JFactory::getUser();
        $option = JRequest::getVar("option","");
        $layout = JRequest::getVar('layout',""); 
        $view   = JRequest::getVar('view',"");
        $fontend = false; 
        if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
                    
        if (($option=='com_content' ))
            {
            $app    = JFactory::getApplication();
            $db = JFactory::getDBO();
            $nameslst = fieldsattachHelper::getfields($article->id);
     
            
            $fields_tmp0 = fieldsattachHelper::getfieldsForAll($article->id);
            $nameslst = array_merge($fields_tmp0, $nameslst );
        
            $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($article->id, $nameslst);
        
            $nameslst = array_merge( $nameslst, $fields_tmp2 );
                
            $session = JFactory::getSession(); 
            
             
            $error=false;
                    
                    
            //Si existen fields relacionados se mira uno a uno si tiene valores
            if(count($nameslst)>0)
            {
                foreach($nameslst as $obj)
                {
                    $query = 'SELECT   b.required ,b.title FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.articleid='.$article->id .' AND a.fieldsid ='. $obj->id ;
                    //echo $query;      
                    
                    $db->setQuery($query);
                    $valueslst = $db->loadObject();
                            
                    $valor = JRequest::getVar("field_". $obj->id, '', 'post', null, JREQUEST_ALLOWHTML); 
                            
                    //Is required
                    $query = 'SELECT a.title,  a.required, a.type,b.title as titlegroup FROM #__fieldsattach as a INNER JOIN #__fieldsattach_groups as b ON a.groupid   = b.id  WHERE  a.id ='. $obj->id ; 
                    $db->setQuery($query); 
                    $fields_row = $db->loadObject();  
                            
                    if(($fields_row->required) && (empty($valor) && ($fields_row->type != "imagegallery") ) )
                    {
                        $error_text = JText::sprintf('JLIB_FORM_VALIDATE_FIELD_REQUIRED', $fields_row->title." (". $fields_row->titlegroup.")");
                               
                        if($fontend) {
                            //JError::raiseWarning( 100, $error_text." " );
                        }else{
                            $app->enqueueMessage( $error_text." ", 'error'   )   ;
                            $error=true;
                        }
                        
                    }
                        
                    //Save values or required fields
                    //$session->set('field_'.$obj->id , $valor); 
                    //Delete Session if all ok in fieldsHelper line 1010 
                }
            }
            
            
            if($error){  return false; } 
                
                    
            //IF TITLE THEN ACTIVE CONTENT =========================================================================================
        
            $db     = JFactory::getDBO();
            $user   = JFactory::getUser(); 
                     
                    
        
            //-----------------------
             
           
            
            if(!empty($id))
            {   
                $query = 'SELECT  id  FROM #__content WHERE created_by='.$user->get('id').' AND title IS NOT NULL AND state  = -2 AND id='.$article->id;
                $db->setQuery( $query );
                $id = $db->loadResult(); 
                
                $article->state = 1;
            } 
        }
        
    //Joomla 3 not call to the function correctly ?????
    //$this->onContentAfterSave($context, $article, $isNew);
              
    }
        
    /**
    * Save alls fields of cagtegory 
    *
        *  TODO : CLONE FUNCTIO FOR TRANSLATION
        *  TODO : ALL CATEGORIES
        * 
    * @access   public
    * @since    1.5
    */
    public function onContentAfterSaveCategories($context,  $article, $isNew)
    {
           
         //Ver categorias del artículo ==============================================================
        //$idscat = $this->recursivecat($article->id);
        $this->str = fieldsattachHelper::recursivecat($article->id  );
       
        $db = & JFactory::getDBO(); 
        
        //JError::raiseWarning( 100,   " IDDDD CATEGORIES: ".$article->id    );
/*
        $query = 'SELECT a.id, a.type, b.recursive, b.catid FROM #__fieldsattach as a INNER JOIN #__fieldsattach_groups as b ON a.groupid = b.id WHERE b.catid IN ('. $this->str .') AND a.published=1 AND b.published = 1 ORDER BY b.ordering, a.ordering,  a.title';
        $db->setQuery( $query );
        $nameslst = $db->loadObjectList();  
*/

        //***********************************************************************************************
        //Mirar cual de los grupos es RECURSIVO  ****************  ****************  ****************
        //***********************************************************************************************
        /*$cont = 0;
        foreach ($nameslst as $field)
        {
            //JError::raiseWarning( 100, $field->catid ." !=".$article->catid  );
            if( $field->catid != $article->id )
            {
                //Mirar si recursivamente si
                if(!$field->recursive)
                    {
                        //echo "ELIMINO DE LA LISTA " ;
                        unset($nameslst[$cont]);
                    }
            }
            $cont++;
        }*/
        //***********************************************************************************************
        //Create array of fields   ****************  ****************  ****************
        //***********************************************************************************************
        //$fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($article->id);
        //$nameslst = array_merge($fields_tmp0, $nameslst );


        $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($article->id);
        $fields = fieldsattachHelper::getfieldsCategory($article->id);
        $nameslst = array_merge($fields_tmp0, $fields);
        
        //Si existen fields relacionados se mira uno a uno si tiene valores
        
         
         
        if(count($nameslst)>0)
        {
            foreach($nameslst as $obj)
            {
                $query = 'SELECT a.id , b.extras, b.visible FROM #__fieldsattach_categories_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.catid='.$article->id .' AND a.fieldsid ='. $obj->id ;
                  
               
                $db->setQuery($query);
                $valueslst = $db->loadObject(); 
                 
                if(empty($valueslst->id))
                    {
                    
                        //INSERT
                        $valor = JRequest::getVar("field_". $obj->id, '', 'post', 'string', JREQUEST_ALLOWHTML);
                        if(is_array($valor))
                        {
                            $valortxt="";
                            for($i = 0; $i < count($valor); $i++ )
                            {

                                  $valortxt .=  $valor[$i].", ";
                            }
                            $valor = $valortxt;
                        }
                        //INSERT
                        //$valor = str_replace('"','&quot;', $valor );
                        $valor = htmlspecialchars($valor);
                        $query = 'INSERT INTO #__fieldsattach_categories_values(catid,fieldsid,value) VALUES ('.$article->id.',\''.  $obj->id .'\',\''.$valor.'\' )     ';
                        $db->setQuery($query);
                        $db->query();
                        
                      //   JError::raiseWarning( 100,   "   count: ".$query   );

                        //Select last id ----------------------------------
                        $query = 'SELECT  id  FROM #__fieldsattach_categories_values AS a WHERE  a.catid='.$article->id.' AND a.fieldsid='.$obj->id;
                        //echo $query;
                        $db->setQuery( $query );
                        $result = $db->loadObject();
                        $valueslst->id = $result->id; 
                        
                       
                        
                        //Required 
                        

                    }
                    else{
                        //UPDATE
                        $valor = JRequest::getVar("field_". $obj->id, '', 'post', 'string', JREQUEST_ALLOWHTML); 
                        if(is_array($valor))
                        {
                            $valortxt="";
                            for($i = 0; $i < count($valor); $i++ )
                            {
                                  $valortxt .=  $valor[$i].", ";
                            }
                            $valor = $valortxt;
                        }
                        //$valor = str_replace('"','&quot;', $valor );
                        $valor = htmlspecialchars($valor);
                        $query = 'UPDATE  #__fieldsattach_categories_values SET value="'.$valor.'" WHERE id='.$valueslst->id ;
                        $db->setQuery($query);
                     //   JError::raiseWarning( 100, $query  );
                        $db->query();
                    }

                    //Acción PLUGIN ========================================================
                    JPluginHelper::importPlugin('fieldsattachment'); // very important
                    $query = 'SELECT *  FROM #__extensions as a WHERE a.element="'.$obj->type.'"  AND a.enabled= 1';
                    // JError::raiseWarning( 100, $obj->type." --- ". $query   );
                    $db->setQuery( $query );
                    $results = $db->loadObject();
                    if(!empty($results))
                    {

                        $function  = "plgfieldsattachment_".$obj->type."::action( ".$article->id.",".$obj->id.",".$valueslst->id.");";
                        //  JError::raiseWarning( 100,   $function   );
                        eval($function);
                    }

                    //JError::raiseWarning( 100,   " IDDDD CATEGORIES: ". $obj->id   ); 
                    //TODO Insert in category text 
                    //COMMENTED FOR A POSIBLE ERROR IN DESCRIPTION - Cristian 04-09-2014
                    $this->insertinDescription($article->id, $obj->id, $valueslst->visible);
            }
        } 
    return true;

    }
    
    /**
    * reset To Description
    *
    * @access   public
    * @since    1.5
    */
    public function resetToDescription($id, $fieldsid,$cadena)
    {
        //$patron ="/{\d+}/i";
       
        $patron = "{fieldsattach_".$fieldsid."}";
        $sustitucion="";
        $cadena = str_replace($patron, $sustitucion, $cadena);

        $cadena = str_replace("<p></p>", "", $cadena);


        return $cadena ;
    } 


    /**
    * Insert fields in category description
    *
    * @access   public
    * @since    1.5
    */
    public function insertinDescription($id, $fieldsid, $visible)
    {
        $db = & JFactory::getDBO();
        $query = 'SELECT description  FROM #__categories as a WHERE a.id= '.$id ;

        //$patron ="/{\d+}/i";
        $patron = "{fieldsattach_".$fieldsid."}";
         
        //JError::raiseWarning( 100, "FIEL: ". $fieldsid." : ".$query  );
        //JError::raiseWarning( 100, "patron;  ".$patron  );
        $sustitucion = "";

        $db->setQuery( $query );
        $results = $db->loadObject();
        if(!empty($results)){
            $cadena = $results->description;

            $cadena = $this->resetToDescription($id, $fieldsid,$results->description); 
             
        }

        if($visible==1) $cadena = $cadena . $patron;

        //JError::raiseWarning( 100, "cadna: ".$cadena );

        $query = 'UPDATE  #__categories SET description="'.(addslashes($cadena)).'" WHERE id='.$id ;
        $db->setQuery($query);
         //JError::raiseWarning( 100, $patron  );
        $db->query();
    }
 

    /**
    * Save alls fields of article - SAVE THE FIELDS VALUES
    *
    * @access   public
    * @since    1.5
    */
    public function onContentAfterSave($context, $article, $isNew)
    {   
        $app    = JFactory::getApplication();
        $user   = JFactory::getUser();
        $option = JRequest::getVar("option","");
        $layout = JRequest::getVar('layout',"");
        $extension = JRequest::getVar('extension',"");
        $view= JRequest::getVar('view',"");



        $sitepath = JPATH_BASE ;
        $sitepath = str_replace ("administrator", "", $sitepath); 
        $sitepath = JPATH_SITE;
        $fontend = false;
        if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
        if(JRequest::getVar("a_id")>0) $fontend = true;

         //CATEGORIES ==============================================================
          if (($option=='com_categories' && $layout=="edit" && $extension=="com_content"  ))
             {
                 $backendcategory = true;
                 $backend=true;
                // JError::raiseWarning( 100,   " IDDDD CATEGORIES: "   );
                 $this->onContentAfterSaveCategories($context, $article, $isNew);
                 //$this->createDirectory($article->id); 
             }

         //Crear directorio ==============================================================
        /* if (($option=='com_content' && $view=="article"   )||($fontend))
         {
             $this->createDirectory($article->id); 
         }*/ 
         //============================================================================
        // JError::raiseWarning( 100, "ARTICLE ID: ".  $article->id  );
        //COPY AND SAVE LIKE COPY
        /*  if ( (JRequest::getVar("id") != $article->id) && (empty( $article->id) )   )  {
            
        //  if( (JRequest::getVar("id") != $article->id && (!empty( $article->id))   && ($article->id>0) && (JRequest::getVar("id")>0))  ){
                $oldid = JRequest::getVar("id")  ; 
                
        //JError::raiseWarning( 100, "ARTICLE ID: ".  $article->id  );
                //$this->copyArticle($article, $oldid); 
            }*/
              
            
        if (($option=='com_content' && $layout=="edit" ) || $fontend)
        {
            $db = JFactory::getDBO();
            $nameslst = array();
            if(!empty($article->id))
            {
                $nameslst = fieldsattachHelper::getfields($article->id);
            
            

                // JError::raiseWarning( 100, "NUMEROOO: ". count($nameslst) ." - ".$article->catid );
                //***********************************************************************************************
                //create array of fields  ****************  ****************  ****************
                //***********************************************************************************************
                $fields_tmp0 = fieldsattachHelper::getfieldsForAll($article->id);
                $nameslst = array_merge($fields_tmp0, $nameslst );

                $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($article->id, $nameslst);

                $nameslst = array_merge( $nameslst, $fields_tmp2 );
            }
            
            //Si existen fields relacionados se mira uno a uno si tiene valores
             
            if(count($nameslst)>0)
            {
                //JError::raiseWarning( 100, "onContentAfterSave ID: ".  $article->id  );
                foreach($nameslst as $obj)
                {

                    $query = 'SELECT a.id, b.required ,b.title, b.extras, b.type FROM #__fieldsattach_values as a INNER JOIN #__fieldsattach as b ON a.fieldsid = b.id WHERE a.articleid='.$article->id .' AND a.fieldsid ='. $obj->id ;
                    //echo $query;
                    
                    $db->setQuery($query);
                    $valueslst = $db->loadObject();
                    if(count($valueslst)==0)
                    {
                        //INSERT 
                        //$valor = JRequest::getVar("field_". $obj->id, '', 'post', null, JREQUEST_ALLOWHTML);
                         $valor = $_POST["field_". $obj->id]; 
                        if(is_array($valor))
                        {
                            $valortxt="";
                            for($i = 0; $i < count($valor); $i++ )
                            {

                                  $valortxt .=  $valor[$i].", ";
                            }
                            $valor = $valortxt;
                        }
                        
                        //GET TYPE
                        $query = 'SELECT type FROM  #__fieldsattach     WHERE  id ='. $obj->id ;
                        $db->setQuery($query);
                        $type = $db->loadResult();
                        
                        //remove vbad characters
                        //$valor = preg_replace('/[^(\x20-\x7F)]*/','', $valor);
                        
                        if($type == "listunits"){
                              
                        }else{
                             $valor = htmlspecialchars($valor);
                        } 
                        //INSERT 
                        $query = 'INSERT INTO #__fieldsattach_values(articleid,fieldsid,value) VALUES ('.$article->id.',\''.  $obj->id .'\','.$db->quote($valor).' )     ';
                        $db->setQuery($query);
                        $db->query();

                        //Select last id ----------------------------------
                        //Cristian 23_09_2013
                        $valueslst->id = $db->insertid();
            
                      
                        
                    }else
                    {
                        //UPDATE 
                        if(isset($_POST["field_". $obj->id]))
                        { 
                            $valor = $_POST["field_". $obj->id]; 
                         
                            if(is_array($valor))
                            { 
                                $valortxt="";
                                for($i = 0; $i < count($valor); $i++ )
                                { 
                                    $valortxt .=  $valor[$i].", ";
                                }
                                $valor = $valortxt;
                            }
                        
                            //remove vbad characters
                            //$valor = preg_replace('/[^(\x20-\x7F)]*/','', $valor);
                        
                            //$valor = str_replace('"','&quot;', $valor );
                            //$valor = htmlspecialchars($valor);
                            //Remove BAD characters ****
                            $valor = preg_replace('/border="*"*/','', $valor);
                        
                            if($valueslst->type == "listunits"){
                              
                            }else{
                                $valor = htmlspecialchars($valor);
                            }
                        
                            $query = 'UPDATE  #__fieldsattach_values SET value="'.$valor.'" WHERE id='.$valueslst->id .' AND articleid='.$article->id ;
                            $db->setQuery($query);
                            //JError::raiseWarning( 100, $query  );
                            $db->query(); 
                        } 
                    }

                    //Acción PLUGIN ========================================================
                    JPluginHelper::importPlugin('fieldsattachment'); // very important 
                    $query = 'SELECT *  FROM #__extensions as a WHERE a.element="'.$obj->type.'"  AND a.enabled= 1';
                    // JError::raiseWarning( 100, $obj->type." --- ". $query   );
                    $db->setQuery( $query );
                    $results = $db->loadObject();
                    if(!empty($results))
                    {
                        
                        $function  = "plgfieldsattachment_".$obj->type."::action( ".$article->id.",".$obj->id.",".$valueslst->id.");";
                       // JError::raiseWarning( 100,   $function   );
                        eval($function);
                    }
            
             
                   
                //END COPY
                }

                //COPY AND SAVE LIKE COPY
                if ( (JRequest::getVar("id") != $article->id) && (!empty( $article->id) )   )  
                {
                
                    $oldid = JRequest::getVar("id")  ; 
                
                    //JError::raiseWarning( 100, "ARTICLE ID: ".  $article->id  );
                    $this->copyArticle($article, $oldid); 
                }

            } 
        }

    return true;
    }
    
    public function onBeforeCompileHead() {
 
        
         
    }
 
    /**
    * Injects Insert Tags input box and drop down menu to adminForm
    *
    * @access   public
    * @since    1.5
    */
    function onBeforeRender()
    {

        $db = JFactory::getDBO();

        $id = JRequest::getVar('id'); 

        $view = "articles";
        $layout = "";
        $layout  = JRequest::getVar('layout'); 
        $option = JRequest::getVar('option'); 
        $view   = JRequest::getVar('view', ''); 

        if( ($option  == "com_content") && ($view == 'articles') && ($layout != 'modal'))
        { 
            $query = 'SELECT *  FROM #__content as a WHERE title= "[title]"'; 
            $db->setQuery( $query );
            $results = $db->loadObjectList();
            if(count($results)>0)
            {
                 $query = 'DELETE FROM  #__content WHERE title= "[title]"' ;
                $db->setQuery($query);
                $db->query();

                //REDIRECT

                header('Location: '.$_SERVER['PHP_SELF'].'?option=com_content');
                exit(); //optional

            }
        }

        if( ($option  == "com_content") && ($view == 'articles') && ($layout == 'modal'))
        { 
             
            $document =  JFactory::getDocument();
            $script = 'jQuery(window).load(function() { ';  

            $script .= 'jQuery( ".table-condensed td" ).each(function( index ) {';
            $script .='var elvalor = jQuery( this ).text();';
           
            $script .= 'elvalor = elvalor. replace(/ /g,"");'; 
            //$script .= 'var elvalor = String( jQuery( this ).text() ).replace(/^\s+|\s+$/gm,"")'; 
            
           // $script .='alert( elvalor );';
            $script .='if(elvalor.indexOf("[title]")>-1){ jQuery( this ).parent().hide();}';
            //$script .= 'if( elvalor == "106"){ console.log("encontrado ID"); }';    
            $script .= '});';

            /*$script .= "num= jQuery('ul#myTabTabs li').length;"; 
            for($i = 0; $i<=$cont; $i++)
            {
                 
                $script .= "var lastli = jQuery('ul#myTabTabs li:eq('+(num-1)+')');"; 
                $script .= "jQuery('ul#myTabTabs li:first').after(lastli);";
                 
            } */
           
            $script .=  '});';
            $document->addScriptDeclaration($script);


        }


           
         

        //EDIT ARTICLE =====================================================================
        if (!$id)
        {
            $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
            @$id = $cid[0];

            $view = JRequest::getVar('view');
            if ($view =='article') $path = '';
            else $path = '..'.DS;
        }
        $task = JRequest::getVar('task');
        $option= JRequest::getVar('option');
        $id= JRequest::getVar('id', JRequest::getVar('a_id'));
        
        $user = JFactory::getUser();
        $task = JRequest::getVar('task');
        $option= JRequest::getVar('option');
        $id= JRequest::getVar('id', JRequest::getVar('a_id'));

        $view= JRequest::getVar('view');
        $layout= JRequest::getVar('layout');
        
        $fontend = false; 
        if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
         
        $backend = false;
        if( $option=='com_content' &&  $layout == 'edit') $backend = true;

        $backendcategory = false;
        if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('view')=="category"  && JRequest::getVar('extension')=="com_content"  ))
        {
            $backendcategory = true;
            $backend=true;
        }

        //GET ID IN FRONTEND v3.3 or upper **
        if( $fontend ) $id=JRequest::getVar('a_id');


        
        if(!empty($id)){ 

            if (($backend ) || ( $fontend )   )
            {
                
                $fields = array();

                 
                
                if($backendcategory)
                {
                    if(!empty($id))
                    {
                        $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                        $fields = fieldsattachHelper::getfieldsCategory($id);
                        $fields = array_merge($fields_tmp0, $fields);
                    }
                   
                }else{

                     
                    if(!empty($id))
                    {   
                        $fields_tmp0 = fieldsattachHelper::getfieldsForAll($id);  
                        $fields_tmp1 = fieldsattachHelper::getfields($id); 
                        $fields_tmp1 = array_merge($fields_tmp0, $fields_tmp1);

                
                        
                        $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($id, $fields_tmp1); 
                        $fields = array_merge($fields_tmp1, $fields_tmp2);
                    }

                     
                     
                }
                
                //******
                //INPUTS ================================================
                     
                $this->exist=false;
            
                $this->fields = $fields; 
            
                //inputs RENDER ====================================================================
                $idgroup=-1;
                $titlegropu="";
                $this->exist  = false;
                $cont = -1;
                $cuantos_en_str=0;
                //$field->position=1;
                $str="";
                
                //$('ul#list li:first').after('ul#list li:eq(1)')
                //If J3.1 *******
                if ( version_compare( JVERSION, '3.1', '>' ) == 1) 
                {
                     

                    if(count($fields)>0)
                    { 
                        foreach($fields as $field)
                        {
                        
                            //if($field->position == 1){
                            //JError::raiseNotice(500, "FIELD ".$field->id);
                            
                            if($field->idgroup != $idgroup)
                            {
                                $cont++;
                                //JError::raiseNotice(500, "FIELD ".$field->title);
                                if($idgroup > 0)
                                { 
                                       
                                    if($this->exist ==true) 
                                    {
                                        $str .= '</div> ';
                                        //JError::raiseNotice(500, "Escribir content");
                                        $myTabContent = JLayoutHelper::render('libraries.cms.html.bootstrap.starttabset', array('selector' => 'myTab'));
                                        $document = JFactory::getDocument();
                                        $content = $document->getBuffer('component');
                                        $content = str_replace($myTabContent, $myTabContent.JHtml::_('bootstrap.addTab', 'myTab', 'tabID'.$idgroup, $titlegropu).$str.JHtml::_('bootstrap.endTab'), $content);
                                        $document->setBuffer($content, array('type' => 'component', 'name' => '', 'title' => ''));
                                        $str="";
                                          
                                    }
                                } 
                                  
                                $this->exist =true;
                                $cuantos_en_str++;
                                 
                  
                                $str .= '<div class="tab-panewww" id="fiedlsattachTab_'.$field->idgroup.'"  >';
                                if(!empty($field->descriptiongroup)) $str .= '<div class="desc">'.$field->descriptiongroup.'</div>';
                
                                  
                            }
                            $idgroup = $field->idgroup;
                            $titlegropu = $field->titlegroup;
                               
                          
                            //NEW GET PLUGIN ********************************************************************
                            JPluginHelper::importPlugin('fieldsattachment'); // very important
                            //select
                              
                            if(empty($this->array_fields)) $this->array_fields = fieldsattachHelper::get_extensions() ;
                               
                            if(count($this->array_fields )>0)
                            {
                                foreach ($this->array_fields as $obj)
                                {
                                    
                                    $function  = "plgfieldsattachment_".$obj."::construct1();";
                                    
                                    //$base = JPATH_BASE;
                                    //$base = str_replace("/administrator", "", $base);
                                    $base =  JPATH_SITE;
                                    $file = $base.'/plugins/fieldsattachment/'.$obj.'/'.$obj.'.php';
                                    
                                       if( JFile::exists($file))
                                       {
                                        //file exist
                                        eval($function);
                                        $function  =  'plgfieldsattachment_'.$obj."::getName();";
                      
                                        eval("\$plugin_name =".  $function."");
                                        //$str .= $field->type." == ".$plugin_name."<br />";
                                        eval($function);
                      
                      
                                        //JError::raiseNotice(500, "sssssdsf");
                                            
                                        if ($field->type ==  $plugin_name ) {
                                            if($backendcategory){ 
                                                $value = JRequest::getVar("field_".$field->id, fieldsattachHelper::getfieldsvalueCategories(  $field->id, $id), 'post', 'string', JREQUEST_ALLOWHTML);
                                               // JError::raiseNotice(500, "sssssdsf");
                                                 //$value = JRequest::getVar("field_".$field->id, $this->getfieldsvalueCategories(  $field->id, $id));
                       
                                            }else{
                                                // $value = JRequest::getVar("field_".$field->id,fieldsattachHelper::getfieldsvalue(  $field->id, $id), 'post', 'string', JREQUEST_ALLOWHTML);
                                                $value ="";
                                                if(isset($_POST["field_".$field->id]))
                                                 $value = $_POST["field_".$field->id]; 
                                                 else {
                                                 $value = fieldsattachHelper::getfieldsvalue($field->id, $id);
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
                                             
                                            $str .= '<div class="control-group"><label class="control-label"  for="field_'.$field->id.'">' . $field->title .'</label> '; 
                                            if($field->required) {$str .= '<span>(*)</span>';}
                                            $str .= '<div class="controls">'.$tmp.  '</div>';
                                            $str .= '</div>';
                                            //Reset field of category description =====================
                                               // fieldsattachHelper::resetToDescription($id, $field->id, &$body);
                                               //$this->resetToDescription($id, $field->id);
                                        }
                                        
                  
                                    }
                                }
                            } 
                            //END inputs RENDER =========================================================
                        }
                        $str .= '</div>';
                     //END INPUT ============================================
                    }
                    
                     
                    //*******
                    //nav-tabs
                    if( $fontend ) 
                    {

                        /*$myTabContent = JLayoutHelper::render('libraries.cms.html.bootstrap.starttabset', array('selector' => 'myTab'));
                        $document = JFactory::getDocument();
                        $content = $document->getBuffer('component');
                        $content = str_replace($myTabContent, $myTabContent.JHtml::_('bootstrap.addTab', 'myTab', 'tabID'.$idgroup, $titlegropu).$str.JHtml::_('bootstrap.endTab'), $content);
                        $document->setBuffer($content, array('type' => 'component', 'name' => '', 'title' => ''));
                        */
                    
                    }else{

                        $myTabContent = JLayoutHelper::render('libraries.cms.html.bootstrap.starttabset', array('selector' => 'myTab'));
                        $document = JFactory::getDocument();
                        $content = $document->getBuffer('component');
                        $content = str_replace($myTabContent, $myTabContent.JHtml::_('bootstrap.addTab', 'myTab', 'tabID'.$idgroup, $titlegropu).$str.JHtml::_('bootstrap.endTab'), $content);
                        $document->setBuffer($content, array('type' => 'component', 'name' => '', 'title' => ''));
                    }
                }
                
                //***************
                //$('ul#list li:first').after('ul#list li:eq(1)')
                $document =  JFactory::getDocument();
                //$(window).load()
                //$document->addScript('/plugins/system/sjdinfinitescroll/jquery.infinitescroll.js');
                $script = 'jQuery(window).load(function() { '; 
                $script .= "tt = jQuery('#myTabTabs li:eq(1)').html();"; 
                $script .= "num= jQuery('ul#myTabTabs li').length;"; 
                for($i = 0; $i<=$cont; $i++)
                {
                     
                    $script .= "var lastli = jQuery('ul#myTabTabs li:eq('+(num-1)+')');"; 
                    $script .= "jQuery('ul#myTabTabs li:first').after(lastli);";
                     
                } 
                $script .= 'if(jQuery("#jform_title").val() == "[title]") jQuery("#jform_title").val("");';
                
               
                $script .=  '});';
                $document->addScriptDeclaration($script);
            
                
            }
        }//IF no empty ID
        //ENND : If 3.1 *******
    }
        
    function cleandatabase()
    {
        $option = JRequest::getCmd('option', '');
                $view = JRequest::getCmd('view', '');

                //DELETE THE ARTICLES WITHOUT TITLE
                if ($option == 'com_content') 
                        //if ($option == 'com_content' ) 
                { 
                        $db = JFactory::getDBO(  );
                        //$query = 'INSERT INTO #__content(title, catid, created_by, created, state) VALUES ("", '.$filter_category_id.', '.$user->get("id").',"'.$mysqldate.'", -2)     ';
                    //$query = 'DELETE FROM #__content WHERE title="" AND state= -2 ';
                                $query = 'DELETE FROM #__content WHERE title=""';
                                //echo "sss:".$query;
                                //JError::raiseWarning( 100,  JTEXT::_("DELETE:").$query   );
                    $db->setQuery($query);

                    $db->query();   
            
        }  
    }
    /**
    * Injects Insert Tags input box and drop down menu to adminForm
    *
    * @access   public
    * @since    1.5
    */
    function onAfterRender()
    {  

                                
       //SELECT WHERE I AM ******
       if ( !JFactory::getApplication()->isAdmin())
       {
                    $option = JRequest::getCmd('option', '');
                    $view   = JRequest::getCmd('view', '');

                    if ($option == 'com_content'  && $view == 'category')
            {
                         // your processing code here
                         $body = JResponse::getBody();
                         //JResponse::setBody("saa");
                         //TODO WRITE A FIELDS TO CATEGORY EDIT CONTENT ========================
                         //$this->addFieldsInCategory();
                         //$body = $this->onAfterRenderCategory($body);
                         return;
                    }
                }
 
                $body = JResponse::getBody();

                //$model = JController::getInstance("com_content");
                //$state = $model->getModel();
                //$dd = $state->getState("filter.category_id");
                // $state->state->get('filter.category_id'); 
                 
                 $writed = false;
                 $id = JRequest::getVar('id');
                 $str = '';
                 $str_options = '';
                 $exist=false;
                 $exist_options=false;
                 $idgroup= 0; 
                 $editor = JFactory::getEditor();

                 $oneclicksave = $this->params->get('oneclicksave', 1);
             
        //Experimental ***************************************  ****************
        if( ($oneclicksave == 1) ||  (!empty($id))) 
        {

                 //EDIT ARTICLE =====================================================================
                if (!$id)
        {
                        $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
                        @$id = $cid[0];

                        $view = JRequest::getVar('view');
                        if ($view =='article') $path = '';
                        else $path = '..'.DS;
        }
        $task = JRequest::getVar('task');
        $option= JRequest::getVar('option');
                $id= JRequest::getVar('id', JRequest::getVar('a_id'));

                $view= JRequest::getVar('view');
                $layout= JRequest::getVar('layout');
         
                  
                $pos = strrpos(JPATH_BASE, "administrator"); 

                $user = JFactory::getUser();
                //***********************************************************************************************
                //Where we are  ****************  ****************  ****************
                //***********************************************************************************************
                
        
            $fontend = false; 
            if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;
             
            $backend = false;
            if( $option=='com_content' && !empty($pos) &&  $layout == 'edit') $backend = true;
    
            $backendcategory = false;
            if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('view')=="category"  && JRequest::getVar('extension')=="com_content"  ))
            {
                $backendcategory = true;
                $backend=true;
            }
            //EDIT FRONTEND
            if(($fontend)&&($view == "form"))
            {
                //echo "el id".$id."<br>";
                $id = JRequest::getVar( 'a_id');
            }
            
             
             //***********************************************************************************************
            //If we are in admin content edit or frontend edit   ****************  ****************   
            //***********************************************************************************************
            if (($backend ) || ( $fontend )  )
            {
                $body = str_replace('method="post"',   'method="post" enctype="multipart/form-data" ' , $body);
                           
                //DELETE DE ERROR WRONG
                //$body = str_replace('<li>Save failed with the following error: </li>',   '' , $body); 
                //$body .= '<style>.message ul li:last-of-type {display: none;} .message ul li:first-of-type {display: block;}</style>'; 
                
                //Plugin control for the no insert new rows ****************
                //Author: giuppe
                //********************************************************** 
                if(empty($id))
                {
                    
                   //***********************************************************************************************
                   //If NEW Redirect  ****************  ****************
                   //***********************************************************************************************
    
                    if($backend && !$backendcategory )
                    {
                        
                        $id = $this->getlastId();
                        
                        JError::raiseWarning( 100, "CREAR DIR::: ". $id ." -- ". JRequest::getVar("id") );

                        //$catid = $this->state->get('filter.category_id')
                        //jform_catid


                        if(!empty($id)){
                            $url = JURI::base() ."index.php?option=com_content&task=article.edit&id=".$id;

                           // $script = '<script>'
                           // $script = '<//script>'
                            echo "<script>document.location.href='" .  ($url) . "';</script>\n";
                            //exit();
                        }
                    }

                    if($fontend )
                    { 
                        $id = $this->getlastId();
                        JError::raiseWarning( 100, "CREAR DIR2::: ". $id ." -- ". JRequest::getVar("id") );
                        
                        if(!empty($id))
                        {
                            //base64_encode($uri)
                               //$uri = $_SERVER["HTTP_REFERER"];
                               $user    = JFactory::getUser();
                               $userId  = $user->get('id');
                               $uri = JFactory::getURI();
                               $uri = 'index.php?option=com_content&task=article.edit&a_id='.$id;
                               $app = JFactory::getApplication();
                               $app->setUserState('com_content.edit.article.id',$id);
                               $url = JURI::base() ."index.php?option=com_content&view=form&layout=edit&a_id=".$id."&Itemid=".JRequest::getVar("Itemid")."&return=".base64_encode($uri);
                            
                               $button = JHtml::_('link',JRoute::_($url), "TESTO");
           
           
                               echo "<script>document.location.href='" .  ($url) . "';</script>\n";
                               //header('Location: '.$url);
                               //JApplication::redirect($url);

                               exit();
                        } 
                    }

                } 
                
                //***********************************************************************************************
                //create array of fields  ****************  ****************  ****************
                //***********************************************************************************************
                $fields = array();
                $fields = $this->fields;
                /*if($backendcategory)
                {
                    if(!empty($id))
                    {
                        $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                        $fields = fieldsattachHelper::getfieldsCategory($id);
                        $fields = array_merge($fields_tmp0, $fields);
                    }
                   
                }else{
                       
                    $fields_tmp0 = fieldsattachHelper::getfieldsForAll($id);
                    //$fields_tmp1 = $this->getfields($id); 
                    $fields_tmp1 = fieldsattachHelper::getfields($id);
                    $fields_tmp1 = array_merge($fields_tmp0, $fields_tmp1);
            
                    
                    $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($id, $fields_tmp1); 
                    $fields = array_merge($fields_tmp1, $fields_tmp2);
                    //$fields = $fields_tmp0;
                }*/
        
                //***********************************************************************************************
                //create HTML  with new extra fields  ****************  ****************  ****************
                //***********************************************************************************************
                //include('lib/QueryPath-2.1.1/php/QueryPath/QueryPath.php'); 
                include('lib/phpQuery-onefile.php'); //For j3.0
                //include('lib/ganon.php'); //For j3.1.1 o highter
                
                $menuTabstr="";
                if(count($fields)>0)
                {
                  
                    $helper = new fieldsattachHelper();
                    $helper->body = $body;
                    $helper->menuTabstr = $str;
                    $helper->str = $str;
                    $helper->str_options = $str_options;
                    //$helper->getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options, &$body,  &$str, &$str_options);
                    $helper->getinputfields($id, $fields, $backend, $fontend, $backendcategory, $exist_options);
                       
                    $str =   $helper->str; 
                    $menuTabstr = $helper->menuTabstr;
                    
                    //$body =  $helper->body;
                    $exist =   $helper->exist;
                    $exist_options =   $helper->exist_options;
                   
                } 
                
               
                //Javascript for reorder li *************
                /*$script = '<script>jQuery(document).ready(function() { jQuery("#myTabTabs").append(jQuery("li.addtab"));
                    jQuery("#myTabTabs li:first").after( jQuery("li.addtab") );
                });</script>';*/
                 
                 
                if ( version_compare( JVERSION, '3.1', '>' ) == 1) {
                    if( $fontend ) { 
                        $options = array('replace_entities' => TRUE, 'ignore_parser_warnings' => TRUE );


                        //Load Document
                        $doc = phpQuery::newDocument($body);
                        phpQuery::selectDocument($doc);
                        
                        //Find element
                        pq('.nav-tabs li:first-child')->append($menuTabstr);
                        
                        //Backend
                        if($backend) pq('#general')->after($str); 
                        if($backendcategory) pq('#details')->after($str);
                        
                        //Front end
                        if($fontend){
                            if(pq("#editor")->length)
                            {
                                pq('#editor')->after($str);
                                // element exists
                            }else{
                                $str = '</fieldset><fieldset><legend>'.strip_tags($menuTabstr).'</legend>'.$str.'';
                                pq('#adminForm #editor-xtd-buttons')->after($str);  
                            }
                            
                        }
                        
                        //Return HTML
                        $body = pq('')->htmlOuter();
                    }else{
                        //Go to beforeRender

                    }
                    
                    
                } else {
                    //echo "running Joomla! 3.0";
                    $options = array('replace_entities' => TRUE, 'ignore_parser_warnings' => TRUE );
                    //Load Document
                    $doc = phpQuery::newDocument($body);
                    phpQuery::selectDocument($doc);
                    
                    //Find element
                    pq('.nav-tabs li:first-child')->append($menuTabstr);
                    
                    if($backend) pq('#general')->after($str);
                    if($fontend) pq('#editor')->after($str);
                    if($backendcategory) pq('#details')->after($str);
                    
                    //Return HTML
                    $body = pq('')->htmlOuter();
                     
                }
                
                
                
                
                
                //if($fontend) $html('#editor')->after($str);
                //if($backendcategory) $html('#details')->after($str);
                
                //$html->find('#myTabTabs')->append($menuTabstr);
                
                
                
            } 
                
        }
        
        //END Experimental ***************************************  ****************
        if(!$writed) JResponse::setBody($body);
                else JResponse::setBody("");
                   
    }

        /**
    * Injects Insert Tags input box and drop down menu to adminForm
    *
    * @access   public
    * @since    1.5
    */
    function onAfterRenderCategory()
    {
            $db = &JFactory::getDBO(  );
            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
            $db->setQuery( $query ); 
            $results_plugins = $db->loadObjectList();
            
            $body = JResponse::getBody();
            //echo "sssssssssssssssssssssssssssssssss:: ".count($results_plugins);
            $id = JRequest::getVar('id') ;
            if(!empty($id)){
                    $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                    $fields = fieldsattachHelper::getfieldsCategory($id);
                    $fields = array_merge($fields_tmp0, $fields);
                }
            $idgroup = -1;
             
            if(count($fields)>0){
                 $exist = false;
                 //NEW
                 JPluginHelper::importPlugin('fieldsattachment'); // very important
                 foreach($fields as $field)
                    {
                        //select
                        foreach ($results_plugins as $obj)
                        {
                            $function  = "plgfieldsattachment_".$obj->element."::construct();";
                            $base = JPATH_BASE;
                            $base = str_replace("/administrator", "", $base);
                            $base = JPATH_SITE;
                            $file = $base.'/plugins/fieldsattachment/'.$obj->element.'/'.$obj->element.'.php';
                            
                            if( JFile::exists($file)){
                                //file exist
                                
                                eval($function);
                            }
                             
                            $i = count($this->array_fields); 
                            //$str .= "<br> ".$field->type." == ".$obj->element;
                            if (($field->type == $obj->element)&&($field->visible ))
                            {
                                $function  = "plgfieldsattachment_".$obj->element."::getHTML(".$id.",". $field->id.", true);";
                                //$sustitucion  = "<br> ".$function ;
                                // echo "<br>".$function;
                                eval("\$sustitucion   =".  $function."");
                               
                               // $str .= $function;
                            }
                        }
                         //echo "xxxxxxxxxx dd:".$idgroup;
                        // $body .=    $field->titlegroup.'sdddddddddddddddddddddddddddddddddddddddddddddddd<br>ss';
                        if(($field->visible )){
                            
                             $patron = "{fieldsattach_".$field->id."}"; 
                            // echo $patron;
                             $body = str_replace($patron, $sustitucion, $body) ;
                             $exist=true;
                             $idgroup = $field->idgroup;
                        }else{
                             $patron = "{fieldsattach_".$field->id."}"; 
                            // echo $patron;
                             $body = str_replace($patron, "", $body) ;
                              
                        }


                    } 
             }
            JResponse::setBody($body);
        }

        /**
    * Create a directory 
    *
    * @access   public
    * @since    1.5
    */
        /*private function createDirectory($id)
        {
            $app = JFactory::getApplication(); 
            //JError::raiseWarning( 100, "CREAR DIR::: ".  $this->path .DS.  $id );
            if(!JFolder::exists($this->path .DS. $id))
             {
                //echo "<br >CREATE PATH __ : ".$this->path .DS.  $article->id;
                //
                if(!JFolder::create($this->path .DS.  $id))
                {
                    JError::raiseWarning( 100,   JTEXT::_("I haven't created:").$this->path .DS.  $id );
                }else
                {
                    $app->enqueueMessage( JTEXT::_("Folder created:").$this->path .DS. $id)   ;
                }
             } 
        }
*/
        /**
    * Get last id of content articles
    *
    * @access   public
    * @since    1.5
    */

        private function  getlastId()
        {
            
        $this->cleandatabase(); 
            
        $db     = JFactory::getDBO();
        $user   = JFactory::getUser();
        $mysqldate = date( 'Y-m-d H:i:s' );
        
        //-----------------------
        $query = 'SELECT  id  FROM #__content WHERE created_by='.$user->get('id').' AND title= "" ';
         
         
        $db->setQuery( $query );
        $id = $db->loadResult(); 
     
        if(empty($id))
            { 
                //------------------
                //JController/getModel 
                $app = JFactory::getApplication();
                //$filter_category_id = $app->getUserState('com_content.articles.filter.category_id');

        //CRISTIAN - UPDATE FOR J 3.3   - 6-06-2014
        //$filter_category_id = $app->getUserState('com_content.articles.filter')["category_id"];
        //DAN - UPDATE FOR COMPATIBILITY WITH PHP 5.3.10+ (Joomla 3.x minimum requirement) - 7-10-2014
        $filter_category_id = $app->getUserState('com_content.articles.filter'); 

        $filter_category_id["category_id"];
        $filter_category_id = $filter_category_id["category_id"];
        

            if(empty($filter_category_id))
            {
                $body = JResponse::getBody();
                $tmp = explode('name="jform[catid]" value="',$body);
                if(count($tmp)>1)
                {
                    $tmp = explode('"',$tmp[1]);
                    $filter_category_id = $tmp[0];
                }
           
            }
    
          if(empty($filter_category_id)){$filter_category_id = 1;}
          
            //ACCESS DEFAULT VALUE - Cristian 23_09_2013
            $access = 1; 
            $config = new JConfig(); 
            $access = $config->access; 
            
            //Insert content 
            $valor = "";
            $query = ' INSERT INTO #__content(title, catid, created_by, created, state, access) VALUES ("[title]", '.$filter_category_id.', '.$user->get("id").',"'.$mysqldate.'", 1, "'.$access.'")     ';
            $db->setQuery($query);
    
            $db->query(); 
            //-----------------------
            //$id =  $valueslst->id = $db->insertid();
            $id =   $db->insertid();
            /*$query = 'SELECT  id  FROM #__content   ';
            $query .= ' order by id DESC '; 
            $db->setQuery( $query );
            $result = $db->loadObject();
            $id = $result->id;*/
    
            //Crear directorio ==============================================================
            //$this->createDirectory($id);
            
            
            }
        return $id;
        }

        /**
    * Get last id of content articles
    *
    * @access   public
    * @since    1.5
    */

        private function  getcategorylastId()
        {
            $db = & JFactory::getDBO();
            $user =& JFactory::getUser();
            $mysqldate = date( 'Y-m-d H:i:s' );

            //-----------------------
            $query = 'SELECT  id  FROM #__categories WHERE created_user_id='.$user->get(id).' AND title= "" ';

            //echo $query;
            $db->setQuery( $query );
            $id = $db->loadResult(); 
            if(empty($id))
                {
                $valor = "";
                $query = 'INSERT INTO #__categories(title, extension, created_user_id, created_time, published) VALUES ("", "com_content", '.$user->get("id").',"'.$mysqldate.'", 0)     ';
                $db->setQuery($query);
                $db->query();
               // echo "<br>".$query;
                //-----------------------
                $query = 'SELECT  id  FROM #__categories   ';
                $query .= ' order by id DESC ';
                //echo "<br>".$query;
                $db->setQuery( $query );
                $result = $db->loadObject();
                $id = $result->id;

                //Crear directorio ==============================================================
                //$this->createDirectory($id);
                }
            return $id;
        } 
        
        /**
    * Get list of fields to category
    *
    * @access   public
    * @since    1.5
    */
        /*private function getfieldsCategory($catid)
        {

            $result = array();
            $db = & JFactory::getDBO();
            $query = 'SELECT a.id, a.language FROM #__categories as a WHERE a.id='. $catid  ;
            $src="";

            $db->setQuery( $query );
            $elid = $db->loadObject();
            $idioma = $elid->language; 
            //$this->recursivecat($elid->id, "");
            fieldsattachHelper::recursivecat($elid->id, & $src);

            if(!empty($elid)){
                $db = & JFactory::getDBO();

                $query = 'SELECT a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid IN ('. $src .') AND a.published=1 AND b.published = 1 AND a.group_for = 1 ';
                //echo "Language: ".$query;
                if (  ($elid->language == $idioma ) ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                }
                 $query .='ORDER BY a.ordering, a.title, b.ordering';
                // echo $query;
                $db->setQuery( $query );
                $result = $db->loadObjectList();

                //**********************************************************************************************
                //Mirar cual de los grupos es RECURSIVO  ************************************************
                //***********************************************************************************************
                $cont = 0;
                foreach ($result as $field)
                {

                    if( $field->catid != $elid->id )
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
               // return $result;
            }
             return $result;

        }

*/
        /**
    * Get list of fields to content
    *
    * @access   public
    * @since    1.5
    */
       /* private function getfields($id)
        {
             
            $result = array();
            $db = & JFactory::getDBO(); 
            $query = 'SELECT a.catid, a.language FROM #__content as a WHERE a.id='. $id  ;
             
            $db->setQuery( $query );
            $elid = $db->loadObject();
            if(!empty($elid)){
            $idioma = $elid->language;

            $this->recursivecat($elid->catid, "");
            
            if(!empty($elid)){
                $db = & JFactory::getDBO();

                $query = 'SELECT a.id as idgroup, a.title as titlegroup, a.description as descriptiongroup, a.position,  a.catid, a.language, a.recursive, b.* FROM #__fieldsattach_groups as a INNER JOIN #__fieldsattach as b ON a.id = b.groupid ';
                $query .= 'WHERE a.catid IN ('. $this->str .') AND a.published=1 AND b.published = 1 AND a.group_for = 0 ';
                //echo $elid->language."Language: ".$idioma;
                if (  ($elid->language == $idioma ) ) {
                      $query .= ' AND (a.language="'.$elid->language.'" OR a.language="*" ) AND (b.language="'.$elid->language.'" OR b.language="*") ' ;
                      // echo "filter::". $app->getLanguageFilter();
                      // echo "filter::". JRequest::getVar("language");
                }
                 $query .='ORDER BY a.ordering, a.title, b.ordering';
                 echo $query;
                $db->setQuery( $query );
                $result = $db->loadObjectList(); 

                //**********************************************************************************************
                //Mirar cual de los grupos es RECURSIVO  ************************************************
                //***********************************************************************************************
                $cont = 0;
                foreach ($result as $field)
                {
                    
                    if( $field->catid != $elid->catid )
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
                //return $result;
            }
            }

            return $result;
        }*/

        /**
    * Get value of one field content
    *
    * @access   public
    * @since    1.5
    */
        /*public function getfieldsvalue($fieldsid, $articleid)
        {
            $result ="";
            $db = & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }*/

         /**
    * Get value of one field category
    *
    * @access   public
    * @since    1.5
    
        private function getfieldsvalueCategories($fieldsid, $catid)
        {
            $result ="";
            $db = & JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_categories_values as a WHERE a.fieldsid='. $fieldsid.' AND a.catid='.$catid  ;
            //echo $query;
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))  $return =$elid->value;
            return $return ;
        }
*/
        
         /**
    * Get value of one field content
    *
    * @access   public
    * @since    1.5
    */
        private function getfieldsvaluearray($fieldsid, $articleid, $value)
        {
            $result ="";
            $db = JFactory::getDBO();
            $query = 'SELECT a.value FROM #__fieldsattach_values as a WHERE a.fieldsid='. $fieldsid.' AND a.articleid='.$articleid  ;
            //echo "<br>";
            $db->setQuery( $query );
            $elid = $db->loadObject();
            $return ="";
            if(!empty($elid))
            { 
                $tmp = explode(",",$elid->value); 
                foreach($tmp as $obj)
                {
                    $obj = str_replace(" ","",$obj);
                    $value = str_replace(" ","",$value);
                    //echo "<br>".$obj ."==". $value." -> ".strcmp($obj, $value)." (".strlen($obj).")";
                    if(strcmp($obj, $value) == 0)
                    {
                        //echo "SIIIIIIIIIIIIIIIII" ;
                        return true;
                    }
                }
            }
            return false ;
        }
        
        /**
    * recursive function
    *
    * @access   public
    * @since    1.5
     
        function recursivecat($catid)
        {
             if(!empty($catid)){
                if(!empty($this->str)) $this->str .=  ",";
                $this->str .= $catid ;
                //echo "SUMO:".$str."<br>";
                $db = & JFactory::getDBO();
                $query = 'SELECT parent_id FROM #__categories as a WHERE a.id='.$catid   ;
                //echo $query."<br>";
                $db->setQuery( $query );
                $tmp = $db->loadObject();
                
                if($tmp->parent_id>0) $this->recursivecat($tmp->parent_id);
             }
        }*/

        //IMAGE RESIZE FUNCTION FOLLOW ABOVE DIRECTIONS  
       /*
         private function resize($nombre,$archivo,$ancho,$alto,$id,$filter=NULL)
        {
            $path = JPATH_BASE ;
            $app = JFactory::getApplication();
             
            $arr1 = explode(".", $nombre );
            $tmp = $arr1[1];
             
            $nombre = $path."/".$this->path .DS. $id .DS. $nombre;
             $destarchivo = $this->path .DS. $id .DS. $archivo;
            $archivo =  $path."/".$this->path .DS. $id .DS. $archivo;
 
            //$app->enqueueMessage( JTEXT::_("Name file:  ").$nombre);
            //$app->enqueueMessage( JTEXT::_("New name:  ").$archivo);
             
            if(!file_exists($archivo)){
                JError::raiseWarning( 100, JTEXT::_("Not file exist ")  );
            }
            
            if (preg_match('/jpg|jpeg|JPG/',$archivo))
                {
                $imagen=imagecreatefromjpeg($archivo);
                }
            if (preg_match('/png|PNG/',$archivo))
                {
                $imagen=imagecreatefrompng($archivo);
                }
            if (preg_match('/gif|GIF/',$archivo))
                {
                $imagen=imagecreatefromgif($archivo);
                }
                
            $x=imageSX($imagen);
            $y=imageSY($imagen);
            if (!empty($ancho)) $w = $ancho; else $w = 0;
            if (!empty($alto)) $h = $alto; else $h = 0;

            $app->enqueueMessage( JTEXT::_("ORIGINAL: ")." width:".$x." height:".$y  );

            if($h > 0) { $ratio = ($y / $h); $w = round($x / $ratio);}
            else { $ratio = ($x / $w); $h = round($y / $ratio);}
 

            if(!empty($filter))
            {
                     
                    
                    if($filter =="IMG_FILTER_NEGATE") $filter = 0;
                    if($filter =="IMG_FILTER_GRAYSCALE") $filter = 1;
                    if($filter =="IMG_FILTER_BRIGHTNESS") $filter = 2;
                    if($filter =="IMG_FILTER_CONTRAST") $filter = 3;
                    if($filter =="IMG_FILTER_COLORIZE") $filter = 4;
                    if($filter =="IMG_FILTER_EDGEDETECT") $filter = 5;
                    if($filter =="IMG_FILTER_EMBOSS") $filter = 6;
                    if($filter =="IMG_FILTER_GAUSSIAN_BLUR") $filter = 7;
                    if($filter =="IMG_FILTER_SELECTIVE_BLUR") $filter = 8;
                    if($filter =="IMG_FILTER_MEAN_REMOVAL") $filter = 9;
                    if($filter =="IMG_FILTER_SMOOTH") $filter = 10;
                    if($filter =="IMG_FILTER_PIXELATE") $filter = 11;
                    if(imagefilter($imagen, $filter, 50))
                    { 
                        $app->enqueueMessage( JTEXT::_("Apply filter:").$filter  );
                    }  else {
                        JError::raiseWarning( 100,  JTEXT::_("Apply filter ERROR:").$filter   );
                    }
                    
            }

            // intentamos escalar la imagen original a la medida que nos interesa
            
             if(($w==0)||($h==0)) {$w=$x; $h=$y;}
            //$destino=ImageCreateTrueColor($w,$h);
             $app->enqueueMessage( JTEXT::_("IMAGE RESIZE: ")." width:".$w." height:".$h  );
            $destino = ImageCreateTrueColor($w,$h)
            or JError::raiseWarning( 100, JTEXT::_("Not created image  ")  );
            
            imagecopyresampled($destino,$imagen,0,0,0,0,$w,$h,$x,$y);

            if(!file_exists($archivo)){
                JError::raiseWarning( 100, JTEXT::_("Not file exist ")  );
            }else{ 
                //JFile::delete( $archivo );
                //$app->enqueueMessage( JTEXT::_("DELETE FILE   ").$archivo  );
            }

            $created = false;
            if (preg_match("/png/",$archivo))
                {
                $created = imagepng($destino,$archivo);
                }
            if (preg_match("/gif/",$archivo))
                {
                $created = imagegif($destino,$archivo);
                }
            else
                {
                $created = imagejpeg($destino,$archivo);
               
                }

             if($created){   $app->enqueueMessage( JTEXT::_("CREATE IMAGE OK   ").$archivo  );}
                else{JError::raiseWarning( 100, JTEXT::_("I can't create the image: ".$archivo)  );}
 
            imagedestroy($destino);
            imagedestroy($imagen);
        }
        * */
         
        /*
        private function get_extensions()
        {
            $array_fields  = array();
            $db = &JFactory::getDBO(  );
            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
            $db->setQuery( $query );

            $results = $db->loadObjectList();
            foreach ($results as $obj)
            {
                $array_fields[count($array_fields)] = $obj->element;
            }
            return $array_fields;
        } 
         */

        function addFieldsInCategory()
        {
             $body = JResponse::getBody();
             //echo "---------------------". $body;
             //$body = "";
        }
        
        
        /**
    * Copy the article with extrafields
    *
    * @access   public
    * @since    1.5
    */
    public function copyArticle($article,$oldid)
        {

        //JError::raiseWarning( 100, "Copy article ".$article->id . " old: ".$oldid );
                
        $app = JFactory::getApplication(); 
                //COPY AND SAVE LIKE COPY   
                $newid = $article->id;
        
        if(!empty($oldid)) {
                
            $db = JFactory::getDBO();

            
            //COPY __fieldsattach_values VALUES TABLE
            //$query = 'INSERT INTO #__fieldsattach_values (articleid, fieldsid, value) SELECT ' . $newid . ', fieldsid, value FROM #__fieldsattach_values WHERE articleid = '. $oldid;
            //$db->setQuery( $query );
            //$db->query(); 
             
            //Log
            //plgSystemfieldsattachment::writeLog("function copyArticle log1: ".$query); 
            
            $query = 'INSERT into #__fieldsattach_images (articleid, fieldsattachid, title,  image1, image2, image3, description, ordering, published)'.
                ' SELECT ' . $newid .', fieldsattachid, title,  image1, image2, image3, description, ordering, published FROM #__fieldsattach_images WHERE articleid = '. $oldid ;      
            $db->setQuery( $query );
            $db->query();
            
            //copy documents and images
            $sitepath = JPATH_SITE;
            //COPY  FOLDER IMG-----------------------------
            $path= '/images/documents';
            if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('layout')=="edit" && JRequest::getVar('extension')=="com_content"  )) {
                 $path= '/images/documentscategories';
            } 
            
            $source = $sitepath . $path . '/' .  $oldid . '/';
            $dest = $sitepath .  $path . '/' .  $newid . '/';
            // progress only if source dir exists
            if(JFolder::exists($source)) {
                if(!JFolder::exists($dest))
                {
                    JFolder::create($dest);
                }
                $files =  JFolder::files($source);
            
                foreach ($files as $file)
                { 
                    if(Jfile::copy($source.$file, $dest.$file)) $app->enqueueMessage( JTEXT::_("Copy file ok:") . $file );
                    else JError::raiseWarning( 100, "Cannot copy the file: ".  $source.$file." to ".$dest.$file );
                }
            }
        }
        }
        
       /* public function batch()
        {
            // Initialise variables.
        $input  = JFactory::getApplication()->input;
        $vars   = $input->post->get('batch', array(), 'array');
        $cid    = $input->post->get('cid', array(), 'array');
                
                 $app = JFactory::getApplication();
                 $db    = & JFactory::getDBO();
                

        // Build an array of item contexts to check
        $contexts = array();
        foreach ($cid as $id)
        {
            //echo "<br>ID:::".$id;
                        
                        $app->enqueueMessage( JTEXT::_("JLIB_APPLICATION_SUCCESS_BATCH") . $id )   ;
                        
                        $query = 'SELECT a.version,  a.hits, a.created, a.introtext, a.modified, a.state FROM #__content as a  WHERE  a.id ='. $id ; 
                        $db->setQuery($query); 
                        
                       // echo "<br>".$query;
                        
                        $row = $db->loadObject();  
                        
                        $version = $row->version;
                        $hits = $row->hits;
                        $created = $row->created;
                        $introtext = $row->introtext;
                        $modified = $row->modified;
                        $state = $row->state;
                         

                        
                        $query = 'SELECT a.id FROM #__content as a';
                        $query .=' WHERE ';
                        $query .=' version = "'.$version.'"';
                        $query .=' AND ';
                        $query .=' hits = "'.$hits.'"';
                        $query .=' AND ';
                        $query .=' created = "'.$created.'"';
                        $query .=' AND ';
                        $query .=' introtext = "'.$introtext.'"';
                        // endTime < DATE_SUB(CONVERT_TZ(NOW(), @@global.time_zone, 'GMT'), INTERVAL 30 MINUTE)
                        //$query .=' AND ';
                        //$query .=' modified >= DATE_SUB(CURDATE(),  INTERVAL 30 MINUTE)';
                        $query .=' AND ';
                        $query .=' state = "'.$state.'"';
                        $query .=' AND ';
                        $query .=' id > "'.$id.'"';
                        
                       // echo "<br>".$query;
                        
                        
                        $db->setQuery($query); 
                        $tmp = $db->loadObjectList();
                        
                        if(count($tmp)>0){
                        
                            $newid = $tmp[count($tmp)-1]->id;
                            //GET ARTICLE
                            if(!empty($newid))
                            {
                                $query = 'SELECT a.* FROM #__content as a  WHERE  a.id ='. $newid ; 
                                $db->setQuery($query);  

                                echo "<br><br>SSS: ".$query;

                                $article = $db->loadObject(); 
                                $oldid = $id;
                                $this->copyArticle($article, $oldid); 
                            }
                        }
                        
                        
                        //echo "<br>NEW ID:".$newid;
                        
                        
        }

         
        }
        */
       
       
    function writeLog( $mesg)
    {
        $logs = $this->params->get('logs', '');
                if($logs){
                    
                    //LOG*********
                    jimport('joomla.cron.log');
                    $create_date = date("Y-m-d H:i:s");
                    $mesg = $create_date." - " .$mesg;
                    
                    JLog::addLogger(
                        array(
                             //Sets file name
                             'text_file' => 'plg_system_fieldsattach.log'
                        ),
                        //Sets all JLog messages to be set to the file
                        JLog::ALL,
                        //Chooses a category name
                        'com_fiedslattach'
                    );
                    
                    
                    JLog::add($mesg, JLog::WARNING, 'com_fiedslattach');
                    //LOG*********
                }
    }



}