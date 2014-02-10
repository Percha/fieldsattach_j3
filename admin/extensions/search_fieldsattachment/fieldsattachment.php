<?php
/**
* @package		perchagoglemaps
* @copyright            Cristian Grañó
* @license		GNU/GPL, see LICENSE.php 
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

global $sitepath;
$sitepath = JPATH_BASE ;
$sitepath = str_replace ("administrator", "", $sitepath);  
JLoader::register('fieldattach',  $sitepath.DS.'components/com_fieldsattach/helpers/fieldattach.php');

 
class  plgAdvancedsearchfieldsattachment extends JPlugin
{
	public $tmp;

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
	function onContentSearch($text, $phrase='', $ordering='', $areas=null, $categories =null, $fieldsfilter=null, $limit=50)
	{ 
                if(is_array($categories)) $categories=implode(",", $categories);
                $results	=array();
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();
 
		require_once JPATH_SITE.'/components/com_content/helpers/route.php';
		require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';
 

		$sContent		= $this->params->get('search_content',		1);
		$sArchived		= $this->params->get('search_archived',		1); 
                $limit			= $this->params->def('search_limit2',		200); 
                
                $ordering               = $this->params->def('ordering',		""); 
                
                $menuitemid = JRequest::getInt( 'Itemid' );
                if ($menuitemid)
                {
                    $menu = JSite::getMenu();
                    $menuparams = $menu->getParams($menuitemid);
                    //echo $menuparams;
                    $params = new JRegistry( $menuparams );
                    $params->merge( $menuparams );
                }else{
                    //If not menu associate search menu
                     $params = new JRegistry(  );
                     JError::raiseWarning( 100, JText::_("MOD_FIELDSATTACHSEARCH_ERROR_LINK_MENU") );
                }

                $ordering= $params->get('ordering');
                
                $rules	= $params->get('paramrules');
		//$condition		= $params->get('conditionmode',		"OR");
                $linkconditions=$params->get('paramlinkconditions');
                $linkconditionsarray=explode(",", $linkconditions);
                
                
		$nullDate           = $db->getNullDate();
		$date               = JFactory::getDate();
		 
		 
		$wheres = array();
                
		$morder = '';
		
		switch ($ordering)
		{
			case 'oldest':
				$order = 'created ASC';
				break;

			case 'popular':
				$order = 'hits DESC';
				break;

			case 'alpha':
				$order = 'title ASC';
				break;

			case 'category':
				$order = 'title ASC, title ASC';
				break;

			case 'newest':
			default:
				$order = 'created DESC';
				break;
		}

                //echo "ORDER:: ".$ordering;
		/*switch ($ordering) {
			case 'oldest':
				$order = '5 ASC';
				break;

			 

			case 'alpha':
				$order = '2 ASC';
				break;

			case 'category':
				$order = '10 ASC, 2 ASC';
				$morder = '2 ASC';
				break;

			case 'newest':
                        default :
				$order = '5 DESC';
				break;
		}*/
                 
		
		

		$rows = array();
		$query	= $db->getQuery(true); 
		 
			 
		//QUERY GENERAL
		$query = plgAdvancedsearchfieldsattachment::getQuerySorted($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit,"","","", $rules);
	     
		//echo "<br><br>".$query;
		$db->setQuery($query, 0, $limit);
		//$db->setQuery($query);
	       
		$results = $db->loadObjectList(); 
	        
		//HREF
        
        if (isset($results))
		{
			foreach($results as $key => $item)
			{
				$results[$key]->href = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug);
			}
		}
		
		 
		return $results;
	}
	 
	
	/**
	* Sort one array of objects by one of the object's property
	*
	* @param mixed $array, the array of objects
	* @param mixed $property, the property to sort with
	* @return mixed, the sorted $array
	*/
	public function sortArrayofObjectByProperty( $array, $property )
	{
	    $cur = 1;
	    $stack[1]['l'] = 0;
	    $stack[1]['r'] = count($array)-1;
	     
		//echo "<br>PROPerty:>>-<< ".$property." --END<br>";
	    do
	    {
		$l = $stack[$cur]['l'];
		$r = $stack[$cur]['r'];
		$cur--;
	
		do
		{
		    $i = $l;
		    $j = $r;
		    $tmp = $array[(int)( ($l+$r)/2 )];
	
		    // split the array in to parts
		    // first: objects with "smaller" property $property
		    // second: objects with "bigger" property $property
		    do
		    {
			//echo "<br>".$i.">>-<<".$property;
			while( $array[$i]->{$property} < $tmp->{$property} ) $i++;
			while( $tmp->{$property} < $array[$j]->{$property} ) $j--;
	
			// Swap elements of two parts if necesary
			if( $i <= $j)
			{
			    $w = $array[$i];
			    $array[$i] = $array[$j];
			    $array[$j] = $w;
	
			    $i++;
			    $j--;
			}
	
		    } while ( $i <= $j );
	
		    if( $i < $r ) {
			$cur++;
			$stack[$cur]['l'] = $i;
			$stack[$cur]['r'] = $r;
		    }
		    $r = $j;
	
		} while ( $l < $r );
	
	    } while ( $cur != 0 );
	
	    return $array;
	
	}
	
	function getQueryFiedsvalue($fieldid)
        {
		  
		
		$db		= JFactory::getDbo();
		$app		= JFactory::getApplication();
		$query		= $db->getQuery(true);
		$tag = JFactory::getLanguage()->getTag();
		$query->select('a.articleid,a.value');
		$query->from('#__fieldsattach_values AS a');
		$query->where('a.fieldsid='.$fieldid);
		
		$db->setQuery($query);
		//echo $query;
		$results = $db->loadObjectList();
		
		//echo "<br>cc:".count($results);
		return $results;
				
	}
           
	function getQuerySorted ($text, $phrase='', $order='', $areas=null, $categories =null, $fieldsfilter=null, $limit=50, $tmpfieldid="", $valuefieldid="", $tmplistids="",$rules="", $fieldOrderid="")
        {
		// echo "RRRULE: ".$rules;
		 
		 $rulesrray = explode( ",", $rules); 
		 
		 //echo "FIFIFIF:".$fieldsfilter."<br>";
		 
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$query	= $db->getQuery(true);
		$tag = JFactory::getLanguage()->getTag();
		
		$whereextra = '';
		
		$arrayfieldsid = explode(",",$fieldsfilter);
			
		//First get a fieldsattach rows
		$fieldsrows = Array();
		foreach ($arrayfieldsid as $fieldsid)
		{
			$tmp1= explode("_",$fieldsid);
			$tmpfieldid= $tmp1[0];
			 
			
			$tmp2 =  plgAdvancedsearchfieldsattachment::getNameField($tmpfieldid);
			//echo "<br>-->".$tmp2;
			$fieldsrows[] =  $this->toAscii($tmp2);
			
		}
		
		$str_query = "";
		
		//Select global
		$str_query = 'SELECT id, title AS title, metadesc, metakey, created AS created, catid, publish_up , ';
		$str_query .='  text, introtext, section,  ';
		$str_query .='slug, ';
		$str_query .='catslug ';
		
		//Fieldsattach rows
		//echo "<br>". implode(",", $fieldsrows)."<br>";
		//$build = ','. implode(",", $fieldsrows). ' ';
		$tmpcont = 0;
		$strtmp = '';
		foreach ($fieldsrows as $fieldtitle)
		{
			
			 
			$strtmp .= ', tmp_'.$fieldtitle ;
			 
			$tmpcont++;
		}
		$str_query .= $strtmp;
		$str_query .= ' FROM ( ';
		
		//Select global
		$str_query .= 'SELECT id, title AS title, metadesc, metakey, created AS created, catid, publish_up , ';
		$str_query .='  text, introtext, section,  ';
		$str_query .='slug, ';
		$str_query .='catslug ';
		//$tmp_query .=',d.fieldsid,d.value ';
		
		//Fieldsattach rows
		//echo "<br>". implode(",", $fieldsrows)."<br>";
		//$build = ','. implode(",", $fieldsrows). ' ';
		$tmpcont = 0;
		$strtmp = '';
		foreach ($fieldsrows as $fieldtitle)
		{
			
			 
			$strtmp .= ', max(tmp_'.$fieldtitle.') as tmp_'.$fieldtitle.' ';
			 
			$tmpcont++;
		}
		$str_query .= $strtmp;
		
		$str_query .= ' FROM ( ';
		//END select global
		
		if(count($fieldsfilter)>0 && empty($tmplistids))
		{
			$whereextra = "";
			$cont_field=0;
			
		       
			 
			//$arrayfieldsid = $fieldsfilter;
			$conttotal= 0;
			foreach ($arrayfieldsid as $fieldsid)
			{
			 
			$tmp_query = 'SELECT a.id, a.title AS title, a.metadesc, a.metakey, a.created AS created, a.catid, a.publish_up , ';
			$tmp_query .='CONCAT(a.introtext, a.fulltext) AS text, a.introtext, c.title AS section,  ';
			$tmp_query .='CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug, ';
			$tmp_query .='CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug ';
			//$tmp_query .=',d.fieldsid,d.value ';
			
			//Fieldsattach rows
			//echo "<br>". implode(",", $fieldsrows)."<br>";
			//$build = ','. implode(",", $fieldsrows). ' ';
			$tmpcont = 0;
			$strtmp = '';
			foreach ($fieldsrows as $fieldtitle)
			{
				
				if($conttotal == $tmpcont)
				{
					$strtmp .= ', d.value as tmp_'.$fieldtitle.' ';
				}else{
					$strtmp .= ', "" as tmp_'.$fieldtitle.' ';
				}
				$tmpcont++;
			}
			
			$tmp_query .= $strtmp;
			
			//TABLES
			$tmp_query .='FROM #__content AS a ';
			
			$tmp_query .='INNER JOIN  #__categories AS c ON c.id=a.catid ';
			$tmp_query .='INNER JOIN  #__fieldsattach_values AS d ON d.articleid=a.id ';
			$tmp_query .='INNER JOIN  #__fieldsattach AS e ON d.fieldsid=e.id ';
			 
			//CONDITIONS
			$searchword = JRequest::getVar("searchword");
			$where_fieldattach="";
			//echo "".$phrase;
			if($phrase=="any"){
			    $cont=0;
			    $tmp = explode(" ", $searchword);
			    //echo "count:".count($tmp). " -- ".($tmp[0]);
			    if(count($tmp)>0)
			    {
				if(!empty($tmp[0])){  
					$where_fieldattach =" (";
					foreach($tmp as $obj){  
						$obj	= $db->Quote('%'.$db->getEscaped($obj, true).'%', false);  
						$where =   "(d.value LIKE ".$obj .' OR '."a.title LIKE ".$obj. ' OR ' . "a.introtext LIKE ".$obj .")";
						$where_fieldattach .= $where;
	
						if(count($tmp)>($cont+1)) $where_fieldattach .=  ' AND ';
						$cont++;
					}
	
					$where_fieldattach .=" )";
				}
			    }
			    
			}else{
			    
			    $text		= $db->Quote('%'.$db->getEscaped($searchword, true).'%', false);
			    $where_fieldattach	=   "(d.value LIKE ".$text .' OR '."a.title LIKE ".$text. ' OR ' . "a.introtext LIKE ".$text .")";
		      
	
			}
			//Extra fields 
			 
			$tmp1= explode("_",$fieldsid);
			$tmpfieldid= $tmp1[0];
			$valuefieldid="";
			
			if(count($tmp1)>1) {
			    $valuefieldid = $tmp1[1];
			}
			//echo "<br>BBB1:: ".$valuefieldid."<br>";
			$valuefieldid = plgAdvancedsearchfieldsattachment::getValue($tmpfieldid, $valuefieldid);
			//echo "<br>BBB2:: ".$valuefieldid."<br>";
			//$where .= " ( d.fieldsid = ". $fieldsid. ' AND  d.value LIKE '.$text .')';
			
			//$whereextra = '';
			//echo "<br />AAA".count($arrayfieldsid).">".($cont_field)." VALUE::---".empty($valuefieldid);
			
			
			//if(!empty($tmpfieldid) && !empty( $tmp1[1])){
			/*if(!empty($tmpfieldid) && !empty($valuefieldid))
			{*/
			      
				 
			//LIKE
			/*
			if($rulesrray[$cont_field]=="LIKE"){
				//echo "<br>Ssssad sdasd1:::: ".$tmpfieldid." --- ".$valuefieldid." END<br>";
				$valuefieldidtmp = $db->Quote('%'.$valuefieldid.'%', false);
				//echo "<br>Ssssad sdasd2:::: ".$tmpfieldid." --- ".$valuefieldid." END<br>";
				$whereextra .= " ( d.fieldsid = ". $tmpfieldid. "";
				if(!empty($valuefieldid)) $whereextra .= " AND  d.value LIKE ".$valuefieldidtmp ;
				$whereextra .= " ) ";
			   //echo "<br>Ssssad sdasd3:::: ".$whereextra." END<br>";
			}
			
			//echo "<br>RULE sdasd3:::: ".$rulesrray[$cont_field]." cont:".$cont_field." END<br>";
			
			//EQUAL
			if($rulesrray[$cont_field]=="EQUAL"){
				
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				$whereextra .= " ( d.fieldsid = ". $tmpfieldid.  "";
				if(!empty($valuefieldid)) $whereextra .= " AND  d.value = ".$valuefieldidtmp ;
				$whereextra .= " ) ";
			}
			//NOT EQUAL
			if($rulesrray[$cont_field]=="NOTEQUAL"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				$whereextra .= " ( d.fieldsid = ". $tmpfieldid. "";
				if(!empty($valuefieldid)) $whereextra .= "AND  d.value != ".$valuefieldidtmp ;
				$whereextra .= " ) ";
			}
			 //HIGHER
			if($rulesrray[$cont_field]=="HIGHER"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				$whereextra .= " ( d.fieldsid = ". $tmpfieldid. "";
				if(!empty($valuefieldid)) $whereextra .= "AND  d.value > ".$valuefieldidtmp .")";
				$whereextra .= " ) ";
			}
			 //LOWER
			if($rulesrray[$cont_field]=="LOWER"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				$whereextra .= " ( d.fieldsid = ". $tmpfieldid. "";
				if(!empty($valuefieldid)) $whereextra .= " AND  d.value < ".$valuefieldidtmp .")";
				$whereextra .= " ) ";
			}
			
			//BETWEEN
			if($rulesrray[$cont_field]=="BETWEEN"){
				$tmp = explode("|", $valuefieldid );
				$valuefieldid_1 = $tmp[0];
				$valuefieldid_2="";
				if(count($tmp)>1) $valuefieldid_2 = $tmp[1];
			       
				if(!empty($valuefieldid_1) && !empty($valuefieldid_2)){

				    //Transform mydsql format
				    $type = plgAdvancedsearchfieldsattachment::getType($tmpfieldid);
				    if($type == "date")
				    {
					$valuefieldid_1 = strtotime( $valuefieldid_1 );
					$valuefieldid_1 = date("Y-m-d",$valuefieldid_1);

					$valuefieldid_2 = strtotime( $valuefieldid_2 );
					$valuefieldid_2 = date("Y-m-d",$valuefieldid_2);
				    }

				    $valuefieldid_1 = $db->Quote($db->getEscaped($valuefieldid_1, true), false);
				    $valuefieldid_2 = $db->Quote($db->getEscaped($valuefieldid_2, true), false);


				    $whereextra .= " (d.fieldsid = ". $tmpfieldid. " AND  d.value BETWEEN ".$valuefieldid_1 ." AND ".$valuefieldid_2 .") ";

				}	
			}
			*/
			//echo "<br>SISISIS<br>".$whereextra."<br>";
			//}
		       
		        $whereextra = " ( d.fieldsid = ". $tmpfieldid .")";
			$cont_field++;
			
			
			if(!empty($where_fieldattach)) $where_fieldattach .= " AND ";
			if(!empty($whereextra)) { $whereextra .= " AND ";   }
		       // $query->where( $whereextra. $where_fieldattach  .' a.state=1 AND c.published = 1 AND a.access IN ('.$groups.') ');
			
			$tmp_query .= ' WHERE '.$whereextra.' '.$where_fieldattach.' a.state=1 ';
			
			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter()) {
				$tmp_query .= ' AND a.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ') ';
				$tmp_query .= ' AND c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ') '; 
				 
			}
			
			$tmpcat = explode(",",$categories);
			
			//Remove ,
			$tmpcat = trim($tmpcat, ',');
			
			if(!empty($categories)){
			    
				$tmp_query .= ' AND c.id in (' . $categories . ')'; 
			    
			}
			  
			if(!empty($tmplistids)){
				$tmp_query .= ' AND a.id in (' . $tmplistids . ')'; 
			   
			    
			}
			
			if(!empty($fieldOrderid) && is_numeric($fieldOrderid) && count($fieldsfilter)>0){
			     
			    $tmp_query .= ' AND d.fieldsid = ' . $fieldOrderid; 
			    
			}
			
		        //$tmp_query .= ' ORDER BY   ' . $order;
			
		       
		        $str_query .= $tmp_query." ";
			 
			if(count($arrayfieldsid)-1 > $conttotal)  $str_query .= " UNION ";
			$conttotal++;
			
		    } //For
		     
		} //
		
		$str_query .= ' ) as T';
		
		//WHERE FULL
		
		$tmpcont = 0;
		$first=true;
		$whereextra="";
		
		foreach ($fieldsrows as $fieldtitle)
		{
			
			$fieldsid = $arrayfieldsid[$tmpcont];
		 
			
			$tmp1= explode("_",$fieldsid);
			$tmpfieldid= $tmp1[0];
			$valuefieldid="";
			
			if(count($tmp1)>1) {
			    $valuefieldid = $tmp1[1];
			}
			//echo "<br>BBB1:: ".$valuefieldid."<br>";
			$valuefieldid = plgAdvancedsearchfieldsattachment::getValue($tmpfieldid, $valuefieldid);
			//echo "<br>RULE:: ".$rulesrray[$cont_field]." LLL:".$tmpcont."<br>";
			
			
			$tmpwhere = '';
			
			$tmpand  = '';
			
			//Remove space
			$valuefieldid = trim($valuefieldid);

			
			if( !empty($valuefieldid) )
			{
				$tmpand .=' AND ' ;
			}
			//LIKE
			
			if($rulesrray[$tmpcont]=="LIKE"){
				 
				$type = plgAdvancedsearchfieldsattachment::getType($tmpfieldid);
				switch($type)
				{
				case "selecttree":
					
					$this->SelectTreeChild($valuefieldid);
					$valuefieldid = $valuefieldid.$this->tmp;
					
					//$valuefieldidtmp = $db->Quote(''.$valuefieldid.'', false);
					if(!empty($valuefieldid)) $tmpwhere =  ' tmp_'.$fieldtitle.'  in  ('.$valuefieldid.')' ;

				break;
				case "select":
					 
					//AND VALUE
					$valorselects = fieldattach::getValueSelect( $tmpfieldid , $valuefieldid );
					$valuefieldidtmp = $db->Quote('%'.$valuefieldid.'%', false);  
					if(!empty($valuefieldid) && !empty($valuefieldid)) $tmpwhere =  ' tmp_'.$fieldtitle.'  LIKE '.$valuefieldidtmp ;
				       
					
				break;
				default:
					//echo "<br>Ssssad sdasd1:::: ".$tmpfieldid." --- ".$valuefieldid." END<br>";
					$valuefieldidtmp = $db->Quote('%'.$valuefieldid.'%', false);
					
					
				 
					if(!empty($valuefieldid) && !empty($valuefieldid)) $tmpwhere =  ' tmp_'.$fieldtitle.'  LIKE '.$valuefieldidtmp ;
					
				 
				}
				 

				
				
				
				//echo "<br>Ssssad sdasd3:::: ".$whereextra." END<br>";
			}
			
			//EQUAL
			if($rulesrray[$tmpcont]=="EQUAL"){ 
				 
				$valuefieldidtmp = $db->Quote($valuefieldid, false);
				 
				if(!empty($valuefieldid)) $tmpwhere = '  tmp_'.$fieldtitle.' = '.$valuefieldidtmp ;
				 
			}
			
			//NOT EQUAL
			if($rulesrray[$tmpcont]=="NOTEQUAL"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				 
				if(!empty($valuefieldid)) $tmpwhere =  'tmp_'.$fieldtitle.'!= '.$valuefieldidtmp ;
				 
			}
			 //HIGHER
			if($rulesrray[$tmpcont]=="HIGHER"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
				 
				if(!empty($valuefieldid)) $tmpwhere = ' tmp_'.$fieldtitle.' > '.$valuefieldidtmp ;
				 
			}
			 //LOWER
			if($rulesrray[$tmpcont]=="LOWER"){
				$valuefieldidtmp = $db->Quote($db->getEscaped($valuefieldid, true), false);
			 
				if(!empty($valuefieldid)) $tmpwhere = ' tmp_'.$fieldtitle.' < '.$valuefieldidtmp ;
				 
			}
			
			//BETWEEN
			if($rulesrray[$tmpcont]=="BETWEEN"){
				$tmp = explode("|", $valuefieldid );
				$valuefieldid_1 = $tmp[0];
				$valuefieldid_2="";
				if(count($tmp)>1) $valuefieldid_2 = $tmp[1];
			       
				if(!empty($valuefieldid_1) && !empty($valuefieldid_2)){

				    //Transform mydsql format
				    $type = plgAdvancedsearchfieldsattachment::getType($tmpfieldid);
				    if($type == "date")
				    {
					$valuefieldid_1 = strtotime( $valuefieldid_1 );
					$valuefieldid_1 = date("Y-m-d",$valuefieldid_1);

					$valuefieldid_2 = strtotime( $valuefieldid_2 );
					$valuefieldid_2 = date("Y-m-d",$valuefieldid_2);
				    }

				    //$valuefieldid_1 = $db->Quote($db->getEscaped($valuefieldid_1, true), false);
				    //$valuefieldid_2 = $db->Quote($db->getEscaped($valuefieldid_2, true), false);


				    $tmpwhere = ' (   tmp_'.$fieldtitle.' BETWEEN '.$valuefieldid_1 .' AND '.$valuefieldid_2 .') ';

				}	
			}
			
			//$strtmp .= ' tmp_'.$fieldtitle.'  tmp_'.$fieldtitle.' '; 
			if(!empty($whereextra)) $whereextra .= $tmpand;
			
			$whereextra .=  $tmpwhere;
			 
			$tmpcont++;
		}
		
		
		
		$str_query .= ' GROUP BY id';
		
		$str_query .= ') as W';
		if(!empty($whereextra)) $str_query .= " WHERE ". $whereextra;
		
		$str_query .= ' ORDER BY   ' . $order;
	       //$query->group('a.id');
	       
	       //echo $str_query;
		
	       return $str_query;
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
	/*
	Function for return the name of  extrafield id
	*/
	function getNameField($fieldid)
	{
		$db = &JFactory::getDBO(  );
            
		$query = 'SELECT   a.title  FROM #__fieldsattach as a  WHERE a.id = '.$fieldid;
		//echo $query."<br>";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		$str = "default";
		if(!empty($result)) $str = $result;
		return urlencode($str);
	}
        

        function getType($fieldid)
        {
            $db = &JFactory::getDBO(  );
            
            $query = 'SELECT   a.type  FROM #__fieldsattach as a  WHERE a.id = '.$fieldid;
            //echo $query;
	    
            $db->setQuery( $query );
	    $result = $db->loadResult();
            $str = "";
            if(!empty($result)) $str = $result;
	    return $str;
        }
        
        function getSelectValue($fieldid, $value)
	{
             $extras =  plgAdvancedsearchfieldsattachment::getExtra($fieldid); 
             
                if(count($extras) > 0)
                {
                    foreach ($extras as $linea)
                    {

                        $tmp = explode('|',  $linea);
                        $title = $tmp[0];
                        $valor="";
                        //echo "<br>EXTRAS::". $tmp[0];
                        if(count($tmp)>=2) $valor = $tmp[1];
                        else $valor=$title;
                        $valorcompare = $title; 

                        //CLEAN RETURNS
                        $valorcompare = preg_replace('/\r/u',  '', $valorcompare);
                        $valorcompare = preg_replace('/\n/u',  ' ', $valorcompare); 
                        $valorcompare= ltrim($valorcompare); 
                       // echo "<br>".trim($value)." == ". trim($valorcompare); 
                        if(trim($value) == trim($valorcompare)) $value = $valor; 
                       

                    }
                } 
                return $value;
        }
        
	function getValue($fieldid, $value)
	{
           
           $types =  plgAdvancedsearchfieldsattachment::getType($fieldid);
           
           if( ($types == "select" ) )
           {
             $value = plgAdvancedsearchfieldsattachment::getSelectValue($fieldid, $value ); 
           } 
           
           if( ($types == "selectmultiple" ) )
           {
             
             $value = plgAdvancedsearchfieldsattachment::getSelectValue($fieldid, $value ); 
             //echo "DDD: ".$value ;
           } 
           return $value;
        }
	
	function toAscii($str, $replace=array(), $delimiter='') {
		if( !empty($replace) ) {
		 $str = str_replace((array)$replace, ' ', $str);
		}
	       
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	       
		return $clean;
	}
	
	function SelectTreeChild($value)
	{
		if(!empty($value)){
		 $db = &JFactory::getDBO(  );
		 
			$query = 'SELECT  a.id, a.parent_id FROM #__fieldsattach_optionstree  as a WHERE a.parent_id = '.$value;
		     
			 
			$db->setQuery( $query );
			$rows = $db->loadObjectList();
			
			if(count($rows)>0){
				foreach($rows as $row){
					$this->tmp .= "," . $row->id;
					
					$this->SelectTreeChild($row->id);
					
					 
				}
			}else{
				return $this->tmp ;
			}
		}
		
		
	}


}
