<?php
/**
 * @version		$Id: view.html.php 21593 2011-06-21 02:45:51Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the search component
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_search
 * @since 1.0
 */

global $sitepath;
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath);  
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');

class FieldsattachViewAdvancedSearch extends JViewLegacy
{
	function display($tpl = null)
	{
                //echo JPATH_ADMINISTRATOR.DS.'components/com_search/helpers/search.php';
                //require_once JPATH_ADMINISTRATOR.DS.'components/com_search/helpers/search.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/search.php';

                //LOAD language
                $lang =& JFactory::getLanguage();
                $extension = 'com_search';
                $base_dir = JPATH_SITE;
                //$language_tag = 'en-GB';
                $lang->load($extension, $base_dir, '', true);

		// Initialise some variables
		$app	= JFactory::getApplication();
		$pathway = $app->getPathway();
		$uri	= JFactory::getURI();

		$error	= null;
		$rows	= null;
		$results= null;
		$total	= 0;

		// Get some data from the model
		$areas          = $this->get('areas');
		$state		= $this->get('state');
		$searchword     = $state->get('keyword');
               
                

		$params = $app->getParams();
                
                
                $limit = $params->get("limit");
                $state->set('limit', $limit); 
                $paramrules     = $params->get('paramrules');
                
                //CATEGORIIIIES
                $advancedsearchcategories = JRequest::getVar("advancedsearchcategories"); 
                
                if(empty($advancedsearchcategories)) {
                    $advancedsearchcategories = $state->get('advancedsearchcategories');
                     
                    
                }
                
                if(is_array($advancedsearchcategories)){
                    $advancedsearchcategories = implode(",",$advancedsearchcategories);
                }  
                //FIELDS ******
                
                $fields = JRequest::getVar("fields");
                //$fields = $params->get("fields");
                //echo "FIELDS::".$fields;
                
                if(empty($fields)) {
                    $fields = $params->get("fields");
                    
                }
                
                if(is_array($fields)){ 
                    if(count($fields)>0)
                        $fields = implode(",",$fields);
                      
                } 
                
                if($fields ==",Array"){ $fields = "";}
                

		$menus	= $app->getMenu();
		$menu	= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object($menu)) {
			$menu_params = new JRegistry;
			$menu_params->loadString($menu->params);
			if (!$menu_params->get('page_title')) {
				$params->set('page_title',	JText::_('COM_SEARCH_SEARCH'));
			}
		}
		else {
			$params->set('page_title',	JText::_('COM_SEARCH_SEARCH'));
		}

		$title = $params->get('page_title');
		if ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($params->get('menu-meta_description'))
		{
			$this->document->setDescription($params->get('menu-meta_description'));
		}

		if ($params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
		}

		if ($params->get('robots'))
		{
			$this->document->setMetadata('robots', $params->get('robots'));
		}

		// built select lists
		$orders = array();
		$orders[] = JHtml::_('select.option',  'newest', JText::_('COM_SEARCH_NEWEST_FIRST'));
		$orders[] = JHtml::_('select.option',  'oldest', JText::_('COM_SEARCH_OLDEST_FIRST'));
		$orders[] = JHtml::_('select.option',  'popular', JText::_('COM_SEARCH_MOST_POPULAR'));
		$orders[] = JHtml::_('select.option',  'alpha', JText::_('COM_SEARCH_ALPHABETICAL'));
		$orders[] = JHtml::_('select.option',  'category', JText::_('JCATEGORY'));

		$lists = array();
		$lists['ordering'] = JHtml::_('select.genericlist', $orders, 'ordering', 'class="inputbox"', 'value', 'text', $state->get('ordering'));

		$searchphrases		= array();
		$searchphrases[]	= JHtml::_('select.option',  'all', JText::_('COM_SEARCH_ALL_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'any', JText::_('COM_SEARCH_ANY_WORDS'));
		$searchphrases[]	= JHtml::_('select.option',  'exact', JText::_('COM_SEARCH_EXACT_PHRASE'));
		$lists['searchphrase' ]= JHtml::_('select.radiolist',  $searchphrases, 'searchphrase', '', 'value', 'text', $state->get('match'));

		// log the search
		FieldsattachHelper::logSearch($searchword);

		//limit searchword
		$lang = JFactory::getLanguage();
		$upper_limit = $lang->getUpperLimitSearchWord();
		$lower_limit = $lang->getLowerLimitSearchWord();
		if (FieldsattachHelper::limitSearchWord($searchword)) {
			//$error = JText::sprintf('COM_SEARCH_ERROR_SEARCH_MESSAGE', $lower_limit, $upper_limit);
		}

		//sanatise searchword
		if (FieldsattachHelper::santiseSearchWord($searchword, $state->get('match'))) {
			$error = JText::_('COM_SEARCH_ERROR_IGNOREKEYWORD');
		}

		if (!$searchword && count(JRequest::get('post'))) {
			//$error = JText::_('COM_SEARCH_ERROR_ENTERKEYWORD');
		}

		// put the filtered results back into the model
		// for next release, the checks should be done in the model perhaps...
		$state->set('keyword', $searchword);
		if (($error == null)) {
			$results	= $this->get('data');
			$total		= $this->get('total');
			$pagination	= $this->get('pagination');

			require_once JPATH_SITE . '/components/com_content/helpers/route.php';

			for ($i=0, $count = count($results); $i < $count; $i++)
			{
				$row = &$results[$i]->text;

				if ($state->get('match') == 'exact') {
					$searchwords = array($searchword);
					$needle = $searchword;
				}
				else {
					$searchworda = preg_replace('#\xE3\x80\x80#s', ' ', $searchword);
					$searchwords = preg_split("/\s+/u", $searchworda);
 					$needle = $searchwords[0];
				}

				if(!empty($needle)) $row = FieldsattachHelper::prepareSearchContent($row, $needle);
				$searchwords = array_unique($searchwords);
				$searchRegex = '#(';
				$x = 0;

				foreach ($searchwords as $k => $hlword)
				{
					$searchRegex .= ($x == 0 ? '' : '|');
					$searchRegex .= preg_quote($hlword, '#');
					$x++;
				}
				$searchRegex .= ')#iu';

				//$row = preg_replace($searchRegex, '<span class="highlight">\0</span>', $row);

				$result = &$results[$i];
				if ($result->created) {
					$created = JHtml::_('date',$result->created, JText::_('DATE_FORMAT_LC3'));
				}
				else {
					$created = '';
				}

				$result->text		= JHtml::_('content.prepare', $result->text);
				$result->created	= $created;
				$result->count		= $i + 1;
			}
		}

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();
		if (isset($active->query['layout'])) {
			$this->setLayout($active->query['layout']);
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
                
                //Add filter script
                $doc =& JFactory::getDocument(); 
                $doc->addScript(JURI::root()."components".DS."com_fieldsattach".DS."views".DS."advancedsearch".DS."fields".DS."filter.js" );

		$this->assignRef('pagination',  $pagination);
		$this->assignRef('results',		$results);
		$this->assignRef('lists',		$lists);
		$this->assignRef('params',		$params);

		$this->assign('ordering',		$state->get('ordering'));
		$this->assign('searchword',		$searchword);
		$this->assign('origkeyword',	$state->get('origkeyword'));
		$this->assign('searchphrase',	$state->get('match'));
		$this->assign('searchareas',	$areas); 
                $this->assign('advancedsearchcategories',	$advancedsearchcategories);
                
               
                $this->assign('fields',	$fields);
                $this->assign('paramrules',	$paramrules);
                

		$this->assign('total',			$total);
		$this->assign('error',			$error);
		$this->assign('action',			$uri);

		parent::display($tpl);
	}
        
        function getInfo($fieldid)
        {
            $db = &JFactory::getDBO(  );
            
            $query = 'SELECT a.title, a.type  FROM #__fieldsattach as a  WHERE a.id = '.$fieldid;
            //echo $query;
	    
            $db->setQuery( $query );
	    $result = $db->loadObject();
            $str = "";
            if(!empty($result)) $str = $result;
	    return $str;
        }
        
        function getExtra($fieldsids)
        {
            $db = &JFactory::getDBO(  );
	    $query = 'SELECT a.extras FROM #__fieldsattach as a  WHERE a.id = '.$fieldsids;


            $db->setQuery( $query );
	    $result  = $db->loadResult();
            $extrainfo = explode(chr(13),$result);
            return $extrainfo;
        }
        
        
        function renderSelect($fieldid,$value)
        {
            $required=""; 
            $extras =  FieldsattachViewAdvancedSearch::getExtra($fieldid);
            
            
            //echo $fieldid." - ".$extras[1]."<br />";
            //$str .= "<br> resultado1: ".$tmp;
            //$lineas = explode(chr(13),  $extras);
            
            $str  = '<select name="field_'.$fieldid.'" class="customfields" onchange="changefilter1()" >';
	    $str .= '<option value="" >'. JText::_("COM_FIELDSATTACH_SELECTONE").'</option>'; 
                   
               
		    
            if(count($extras) > 0){
		 
                foreach ($extras as $linea)
                {

                    $tmp = explode('|',  $linea);
                    $title = $tmp[0];
		    //echo $tmp[0]."--".$tmp[1]." -- ".count($tmp)."<br>";
                    $valor="";
                    if(count($tmp)>=2) $valor = $tmp[1];
                    else $valor=$title;
                    //$valor = $title; 
                    
                    //CLEAN RETURNS
                    $valor = preg_replace('/\r/u',  '', $valor);
                    $valor = preg_replace('/\n/u',  ' ', $valor); 
                    
                    if($tmp[1]=="") $valor="";
                    
                    $str .= '<option value="'.ltrim($valor).'" ';
                    if(trim($value) == trim($valor)) $str .= 'selected="selected"'; 
                    $str .= ' >';
                    $str .= $title;
                    $str .= '</option>';

                }
            }
            $str .= '</select>';
            return  $str;
        }
} 