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


// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
//jimport('components.com_search.controller');
JLoader::register('SearchController',  'components/com_search/controller.php');



/**
 * Search Component Controller
 *
 * @package		Joomla.Site
 * @subpackage	com_search
 * @since 1.5
 */
class FieldsattachController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
               // JRequest::setVar('view','search'); // force it to be the search view
                
                //JRequest::setVar('view','advancedsearch'); // force it to be the search view
                
                //echo "VIEW:".JRequest::getVar("view"); 
                $vName = JRequest::getVar("view");
               // echo "<br>vNAEMMME:  ".$vName;
                if($vName == "images" || $vName == "imagesList" || $vName == "fieldsattachimage" || $vName == "fieldsattachimages"  || $vName == "fieldsattachimagesajax"){
                   
                    switch ($vName)
                    {
                            case 'imagesList':
                                    $mName = 'list';
                                    $vLayout = JRequest::getCmd('layout', 'default');

                                    break;

                            case 'images':  
                                    $vLayout = JRequest::getCmd('layout', 'default');
                                    $mName = 'manager';
                                    $vName = 'images';

                                    break;
                            case 'fieldsattachimage':  
                                    $vLayout = JRequest::getCmd('layout', 'default');
                                     
                                    $mName = 'fieldsattachimage';
                                    $vName = 'fieldsattachimage';

                                    break;
                            case 'fieldsattachimages':  
                                    $vLayout = JRequest::getCmd('layout', 'default');
                                     
                                    $mName = 'fieldsattachimages';
                                    $vName = 'fieldsattachimages';

                                    break;
                             case 'fieldsattachimagesajax':  
                                    $vLayout = JRequest::getCmd('layout', 'default');
                                     
                                    $mName = 'fieldsattachimagesajax';
                                    $vName = 'fieldsattachimagesajax';

                                    break;
                                
                                
                    }

                    $document = JFactory::getDocument();
                    $vType		= $document->getType();
                   
                    //echo "<br>VIEW name: ".$vName;
                    //echo "<br>VIEW type: ".$vType;
                    //echo "<br>Model: ".$mName;
                    //echo "<br>LAYOUT: ".$vLayout;

                    // Get/Create the view
                    
                    //
                    $view = $this->getView($vName, $vType);
                    $view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.'/views/'.strtolower($vName).'/tmpl');

                    //echo "<br>type: ".JPATH_COMPONENT_ADMINISTRATOR.'/views/'.strtolower($vName).'/tmpl';
                     
                     // Get/Create the model
                    if ($model = $this->getModel($mName)) {
                            // Push the model into the view (as default)
                            $view->setModel($model, true);
                    }

                    // Set the layout
                    $view->setLayout($vLayout);

                    // Display the view
                    $view->display();
                    
                    //return $this;
                     
                }else{ 

                    return parent::display($cachable, $urlparams);
                }
	}
        /*DELETE IMAGE GALLERY*/
         public function delete()
	{  
                $model = $this->getModel( "fieldsattachimage" );
                $model->delete();
                $fieldsid  = JRequest::getVar('fieldsid'); 
                $link= 'index.php?option=com_fieldsattach&view=fieldsattachimages&tmpl=component&fieldsattachid='.$fieldsid ;
               // $link= 'index.php?option=com_fieldsattach&view=fieldsattachimage&tmpl=component' ; 
                
                $this->setRedirect($link, $msg);
	}
        

	function search()
	{
		// slashes cause errors, <> get stripped anyway later on. # causes problems.
		  $badchars = array('#','>','<','\\');

		$searchword = trim(str_replace($badchars, '', JRequest::getString('searchword', null, 'post')));
		// if searchword enclosed in double quotes, strip quotes and do exact match
		if (substr($searchword,0,1) == '"' && substr($searchword, -1) == '"') {
			$post['searchword'] = substr($searchword,1,-1);
			JRequest::setVar('searchphrase', 'exact');
		}
		else {
			$post['searchword'] = $searchword;
		}
		$post['ordering']	= JRequest::getWord('ordering', null, 'post');
		$post['searchphrase']	= JRequest::getWord('searchphrase', 'all', 'post');
		$post['limit']  = JRequest::getInt('limit', null, 'post');
		if ($post['limit'] === null) unset($post['limit']);

		$areas = JRequest::getVar('areas', null, 'post', 'array');
		if ($areas) {
			foreach($areas as $area)
			{
				$post['areas'][] = JFilterInput::getInstance()->clean($area, 'cmd');
			}
		}

                $fields = JRequest::getVar('fields', null, 'post', 'array');
		if ($fields) {
			foreach($fields as $field)
			{
				$post['fields'][] = JFilterInput::getInstance()->clean($field, 'cmd');
			}
		}

				// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_fieldsattach&view=search');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} elseif (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}

		unset($post['task']);
		unset($post['submit']);

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		$uri->setVar('option', 'com_fieldsattach');


		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false));
	 
            
         }
         
	function advancedsearch()
	{ 
                 
                
		// slashes cause errors, <> get stripped anyway later on. # causes problems.
		  $badchars = array('#','>','<','\\');

		$searchword = trim(str_replace($badchars, '', JRequest::getString('searchword', null, 'post')));
		// if searchword enclosed in double quotes, strip quotes and do exact match
		if (substr($searchword,0,1) == '"' && substr($searchword, -1) == '"') {
			$post['searchword'] = substr($searchword,1,-1);
			JRequest::setVar('searchphrase', 'exact');
		}
		else {
			$post['searchword'] = $searchword;
		}
		$post['ordering']	= JRequest::getWord('ordering', null, 'post');
		$post['searchphrase']	= JRequest::getWord('searchphrase', 'all', 'post');
		$post['limit']  = JRequest::getInt('limit', null, 'post');
		if ($post['limit'] === null) unset($post['limit']);

		$areas = JRequest::getVar('areas', null, 'post', 'array');
		if ($areas) {
			foreach($areas as $area)
			{
				$post['areas'][] = JFilterInput::getInstance()->clean($area, 'cmd');
			}
		}

                $fields = JRequest::getVar('fields', null, 'post', 'array');
		if ($fields) {
			foreach($fields as $field)
			{
				$post['fields'][] = JFilterInput::getInstance()->clean($field, 'cmd');
			}
		}

				// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_fieldsattach&view=advancedsearch');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} elseif (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}

		unset($post['task']);
		unset($post['submit']);
                
                $advancedsearchcategories = JRequest::getVar("advancedsearchcategories");
                $fields = JRequest::getVar("fields");
                
               // echo "----".$fields;

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		$uri->setVar('option', 'com_fieldsattach');
                $uri->setVar('view', 'advancedsearch');
                $uri->setVar('advancedsearchcategories', $advancedsearchcategories);
                $uri->setVar('fields', $fields);
                
                
                  

		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')), false));
	 
            
         }

}
