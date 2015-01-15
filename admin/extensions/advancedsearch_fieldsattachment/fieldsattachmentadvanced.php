<?php
/**
* @package		perchagoglemaps
* @copyright            Cristian Grañó
* @license		GNU/GPL, see LICENSE.php 
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
 
class  plgSearchfieldsattachmentadvanced extends JPlugin
{


	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas()
	{
		 static $areas = array(
		'fieldsattachment' => 'PLG_SEARCH_FIELDSATTACH'
		);
		return $areas; 
	}
       /**
	 * Content Search method
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if the search it to be restricted to areas, null if search all
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
        $text = trim($text);
        $text = trim($text);
        if (empty($text))
        {
            return array();
        }
                
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();

        $searchText = $text;
        if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}
                
                
 
		require_once JPATH_SITE.'/components/com_content/helpers/route.php';
		require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';
 

		$sContent		= $this->params->get('search_content',		1);
		$sArchived		= $this->params->get('search_archived',		1);
		$limit			= $this->params->def('search_limit',		50);

		$nullDate		= $db->getNullDate();
		$date = JFactory::getDate();
		//$now = $date->toMySQL();
                $now = $date->toISO8601();
                
                 
                $text		= $db->Quote('%'.$db->escape($text, true).'%', false);
		 
		$wheres = array();
                //$wheres2[]	= ' ';
                //$wheres[]	= implode(' OR ', $wheres2);
                //$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
		 

		$morder = '';
		switch ($ordering) {
			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.title ASC';
				$morder = 'a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.created DESC';
				break;
		}

		$rows = array();
		$query	= $db->getQuery(true);

		// search articles
		if (  $limit > 0)
		{
                         
			$query->clear();
			$query->select('a.id, a.alias as browsernav, a.title AS title, a.metadesc, a.metakey, a.created AS created, a.catid, '
						.'CONCAT(a.introtext, a.fulltext) AS text, a.introtext, c.title AS section,  '
						.'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug  ');
						//.'"2" AS browsernav');
			$query->from('#__content AS a');

			$query->innerJoin('#__categories AS c ON c.id=a.catid');
                        $query->innerJoin('#__fieldsattach_values AS d ON d.articleid=a.id');
                 
                 
                        
                        
                        $where_fieldattach =   "d.value LIKE ".$text ;

                        $fields = JRequest::getVar("fields");
                        $where_fields="";
                        //$where_fields = "d.fieldsid  = -1 AND";
                        if(!empty($fields))  $where_fields=   "d.fieldsid IN (".implode(",",$fields ). ") AND " ;
                       

                        $query->where(  $where_fields . $where_fieldattach .' AND a.state=1 AND c.published = 1 AND a.access IN ('.$groups.') '

						.'AND c.access IN ('.$groups.')  ');


                        $query->group('a.id');
			$query->order($order);

                        //  $where .= '  AND  a.publish_up >= "'.$year.'-'.$month.'-1" AND  a.publish_up <= "'.$year.'-'.$month.'-31"'  ;

			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter()) {
				$query->where('a.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
				$query->where('c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
			}
                       // echo "<br>SQL::: ".$query;
			$db->setQuery($query, 0, $limit);
			$list = $db->loadObjectList();
                         
			$limit -= count($list); 
			if (isset($list))
			{
				foreach($list as $key => $item)
				{
					$list[$key]->href = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug); 
				}
			}

			$rows[] = $list;
		}
               // JError::raiseWarning( 100, $query  );
               // echo $query;
		$results = array();
		if (count($rows))
		{ 
			foreach($rows as $row)
			{

				$new_row = array();

                                if(count($row)>0){
					foreach($row AS $key => $article) {

		                                $new_row[] = $article;
					} 
					$results = array_merge($results, (array) $new_row);
				}
			}
		}
                
                JRequest::setVar("option", "com_content");
                
		return $results;
	}

	 


}
