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
JLoader::register('fieldattach', 'components/com_fieldsattach/helpers/fieldattach.php');

// require helper file 
JLoader::register('fieldsattachHelper',    'administrator/components/com_fieldsattach/helpers/fieldsattach.php');


/**
 * Example system plugin
 */
class plgContentfieldsattachment extends JPlugin
{
   private $path;
   private $array_fields;
        
	/**
	 * Handle extension uninstall
	 *
	 * @param	JInstaller	Installer instance
	 * @param	int			extension id
	 * @param	int			installation result
	 * @since	1.6
	 */
	function onExtensionAfterUninstall($installer, $eid, $result)
	{
		JError::raiseWarning(-1, 'plgExtensionExample::onExtensionAfterUninstall: Uninstallation of '. $eid .' was a '. ($result ? 'success' : 'failure'));
	}

	/**
	 * @since	1.6
	 */
	function onExtensionBeforeInstall($method, $type, $manifest, $eid)
	{
		JError::raiseWarning(-1, 'plgExtensionExample::onExtensionBeforeInstall: Installing '. $type .' from '. $method . ($method == 'install' ? ' with manifest supplied' : ' using discovered extension ID '. $eid));
	}


        /**
	 * Example after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string		The context for the content passed to the plugin.
	 * @param	object		The content object.  Note $article->text is also available
	 * @param	object		The content params
	 * @param	int			The 'page' number
	 * @return	string
	 * @since	1.6
	 */
	public function onContentBeforeDisplay($context, $article, $params)
	{
		$app = JFactory::getApplication();
                $db = JFactory::getDBO(  );

                $this->url=  'images'.DS.'documents';
                $this->path=   JPATH_BASE .DS.'images'.DS.'documents';
 
               
		 //if ((JRequest::getVar('view')=='category' && JRequest::getVar('option')=="com_content"   )){

              
                  //echo "<br><br>ARTIIIIIIIICLE:". JRequest::getVar('id')." -> ".$article->text." <---";

                // }
                   
 
                if ( JRequest::getVar('option')=="com_content" ){
                    $this->getAll($article);
                }
                 
		 
	}

        private function getAll( $article )
        {
            if(!empty($article->id) && (JRequest::getVar("view")=="article")){
            $db = JFactory::getDBO(  );
            $query = 'SELECT *  FROM #__extensions as a WHERE a.folder = "fieldsattachment"  AND a.enabled= 1';
            $db->setQuery( $query );
            $results_plugins = $db->loadObjectList();
            
            $tmp_fields[]=array();
            $tmp_fields = fieldsattachHelper::getfieldsForAll($article->id);
            
            $fields[]=array();
           
            $fields =fieldsattachHelper::getfields($article->id);

            $fields = array_merge($tmp_fields, $fields );

            $fields_tmp2[]=array();
            $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($article->id, $fields);

            $fields = array_merge($fields, $fields_tmp2 );

            //User access view the layout takes some responsibility for display of limited information.
            $user = JFactory::getUser();
            $groups = $user->getAuthorisedViewLevels();

            if(count($fields)>0){
                        //$body = str_replace('</head>', $header_code.'</head>', $body); 
                          $idgroup =  $fields[0]->idgroup;
                          $str = '';
                          $str_before ='';
                          $cont = 0;
                          foreach($fields as $field)
                            { 
                               //echo "field id: ".$field->id;
                               //echo "<br>field id: ".$field->access;
                               if( in_array($field->access, $groups) ) {
                                //NEW
                                JPluginHelper::importPlugin('fieldsattachment'); // very important
                                //select  
                                foreach ($results_plugins as $obj)
                                {
                                    $function  = "plgfieldsattachment_".$obj->element."::construct1();";
                                    //NEW PACTH CRISTIAN 10_04_2012 =======================
		                                $base = JPATH_SITE;
                                    $file = $base.'/plugins/fieldsattachment/'.$obj->element.'/'.$obj->element.'.php';
                                    //echo "<br>".$file;
                                    if( JFile::exists($file)){
                                        //file exist 
                                        eval($function);
                                                                    // eval($function);
                                        $i = count($this->array_fields);
                                        $this->array_fields[$i] = $obj->element;
                                        //$str .= "<br> ".$field->type." == ".$obj->element;
                                        if (($field->type == $obj->element)&&($field->visible ))
                                        {
                                            $function  = "plgfieldsattachment_".$obj->element."::getHTML(".$article->id.",". $field->id.");";
                                            //$str .= "<br> ".$function ;
                                            if($field->positionarticle==1){
                                               
                                                eval("\$str_before .=".  $function.""); 
                                            }else
                                            {
                                                 eval("\$str .=".  $function.""); 
                                            }
                                            // $str .= $function;  	
                                        }
                                    }
									                  //===================================================== 
                                }

                              
                               //************************************************************************
                              //**************************** titulo campos **********************
                              //***********************************************************************
                              
                              if(($cont+1)< count($fields) ){
                                  if(($idgroup != $fields[$cont+1]->idgroup) &&(!empty($str)))
                                  {
                                      $eltitle = false;
                                      if(isset($field->shortitlegroup)){
                                          if($field->shortitlegroup) $eltitle = true;                                          
                                          }
                                       if($eltitle) $article->text .=  '<h3>'.$field->titlegroup.'</h3>';
                                       $article->text =$str_before.$article->text.$str;
                                       $str ='';
                                  }
                                  $idgroup = $fields[$cont+1]->idgroup;
                                }else{
				                            $article->text    = $str_before.$article->text.$str;
                                    $article->fulltext= $str_before.$article->fulltext.$str;
				                        }
                              }
                             $cont++;
                          }
                 }
            }

          
        } 

} 
