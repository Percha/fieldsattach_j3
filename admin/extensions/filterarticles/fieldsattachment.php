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
		$condition		= $params->get('conditionmode',		"OR");
                $linkconditions=$params->get('paramlinkconditions');
                $linkconditionsarray=explode(",", $linkconditions);
                
                
		$nullDate           = $db->getNullDate();
		$date               = JFactory::getDate();
		 
		 
		$wheres = array();
                
		$morder = '';
               
		switch ($ordering) {
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
		}
                
                //
                 
		/*
                if(count($orderidtmp)==2){
                    if(is_numeric($orderidtmp[0]))
                    {
                      $order = '14 '.$orderidtmp[1];
		      $specialSort = true; 
                    }
                }else{
                    if(is_numeric($orderidtmp[0]))
                    {
                      $order = '14 ';
		      $specialSort = true; 
                    }
                }
                */
		
		

		$rows = array();
		$query	= $db->getQuery(true);
                $query2	= $db->getQuery(true);
		
		$specialSort = false;
                
		
			 
		//QUERY GENERAL
	       $query = plgAdvancedsearchfieldsattachment::getQuerySorted($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit,"","","", $rules);
	     
	       //echo "<br><br>".$query;
	       $db->setQuery($query, 0, $limit);
		
	       
	       $list = $db->loadObjectList();
	       
		
	       
	       //GET LINK URL
	       $limit -= count($list); 
	       if (isset($list))
	       {
		       foreach($list as $key => $item)
		       {
			       $list[$key]->href = ContentHelperRoute::getArticleRoute($item->slug, $item->catslug); 
		       }
	       }
	       //Determine num of filter *******
	       $maxnumfilters=0;
	       //echo "<br>--->".$fieldsfilter."<br>";
	       $arrayfieldsid = explode(",",$fieldsfilter);
		
		foreach ($arrayfieldsid as $fieldsid)
		{
		     
		    $tmp1= explode("_",$fieldsid);
		    $tmpfieldid= $tmp1[0];
		    $valuefieldid="";
		    if(count($tmp1)>1) {
			$valuefieldid = $tmp1[1];
		    }
		    
		    if(!empty($valuefieldid)){
			    $maxnumfilters++;
			    
		    }
		    
		}
	       
	       
	       $fieldsarray = array();
	       
	       //Build a array with all fieldsattach an ID *********************************
	       //Example COL: 	articleId,  total, 	field1id, 	field2id, 	field3id
	       //		2, 		2	Valor1, 	Valor2, 	Valor3
	       //		3, 		2 	Valor12, 	Valor22, 	Valor33
	       //***************************************************************************
	       
	       if (isset($list))
	       {
			//Build
			foreach($list as $key => $item)
			{  
			       $fieldsarray[$item->id]->{'fields_'.$item->fieldsid} = $item->value;
			       if(isset($fieldsarray[$item->id]->total)) $fieldsarray[$item->id]->total = $fieldsarray[$item->id]->total+1;
			       else{ $fieldsarray[$item->id]->total = 1; }
			}
		        
			//DELETE FILTER IF AND
			if($condition=="AND"){
				 foreach($fieldsarray as  $key => $item)
				 {
					  
					 if($item->total<$maxnumfilters) {
						  unset($fieldsarray[$key]);
					 }
					  
				 }
			}
			//print_r($fieldsarray);
			
			//ADD Others extra fields
			foreach ($fieldsarray as  $key => $item)
			{
				//echo "<br>--------------<br>KEY: ->".$key;
				foreach ($arrayfieldsid as $fieldsid)
				{
					//echo "<br>REPASAR: ->".$fieldsid;
					$tmp1= explode("_",$fieldsid);
					$tmpfieldid= $tmp1[0];
					if(!isset($fieldsarray[$key]->{'fields_'.$tmpfieldid} ))
					{
						//echo "<br>CREATE::: ".$key.' -> fields_'.$tmpfieldid;
						$fieldsarray[$key]->{'fields_'.$tmpfieldid} = "";
					} 
					
				}
			     //echo "<br>******************<br>";
			}
			
			//print_r($fieldsarray);
		       
			//ADD PROPIETIES TO ARRAY OBJECTS
			foreach($fieldsarray as  $key => $item)
			{
				foreach($list as $key2 => $item2)
				{
					if($item2->id == $key)
					{
						 
						foreach($item2 as $key3 => $item3)
						{
							$fieldsarray[$key]->{$key3}= $item3;
							 
						}
					}
				}  
			}
	       }
	       
	       
		$tmp = array();
		
		foreach($fieldsarray as  $key => $item)
		{
			if(!empty($item->id)) $tmp[] = $item;
		}
	        $fieldsarray = $tmp;
		
		//echo count($fieldsarray)." ---- cc";
		/*
		for($cont=0; count($fieldsarray)>$cont; $cont++)
		{
			echo "<br>".$cont." --> ".$fieldsarray[$cont]->id." ".$fieldsarray[$cont]->fields_10;
		}
	      */
		
		$orderidtmp = explode(" ",$ordering);
		
		if(is_numeric($orderidtmp[0]))
                {
			//Recovery all data of $orderidtmp
			$allvalues = plgAdvancedsearchfieldsattachment::getQueryFiedsvalue($orderidtmp[0]);
			//ADD PROPIETIES TO ARRAY OBJECTS
			foreach($fieldsarray as  $key => $item)
			{
				foreach($allvalues as $key2 => $item2)
				{
					//echo "<br>AÑADIR".$item2->articleid." == ".$item->id;
					if($item2->articleid == $item->id)
					{
						//echo "<br>AÑADIR".$key;
						$fieldsarray[$key]->{"fields_".$orderidtmp[0]} = $item2->value; 
					}
				}  
			}
			
			//print_r($fieldsarray);
			
			$propertyvar = "fields_".$orderidtmp[0] ;
			//echo "<br>PROPERTY:: ".$propertyvar."<br>";
			
			
			$fieldsarray = plgAdvancedsearchfieldsattachment::sortArrayofObjectByProperty( $fieldsarray, $propertyvar );
			 
			 
			if( count($orderidtmp) > 1) {
				 
				if($orderidtmp[1] == "DESC"){
					 
					$fieldsarray = array_reverse($fieldsarray, true);
					 
				}
				}
                }
                
		$results = $fieldsarray; 
                
                JRequest::setVar("option", "com_content");
		
		 
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
            $query->select('a.id, a.title AS title, a.metadesc, a.metakey, a.created AS created, a.catid, a.publish_up , '
						.'CONCAT(a.introtext, a.fulltext) AS text, a.introtext, c.title AS section,  '
						.'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug'
                                                .',d.fieldsid,d.value
                                                
                                                ');
						//.'"2" AS browsernav');
                                                
			$query->from('#__content AS a');

			$query->innerJoin('#__categories AS c ON c.id=a.catid');
                        $query->innerJoin('#__fieldsattach_values AS d ON d.articleid=a.id');
                        $query->innerJoin('#__fieldsattach AS e ON d.fieldsid=e.id');
 
                        
			/*$query->where('('. $where .')' . 'AND a.catid IN(10,11) AND a.state=1 AND c.published = 1 AND a.access IN ('.$groups.') '
						.'AND c.access IN ('.$groups.') '
						.'AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).') '
						.'AND (a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).')' );
			*/
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
                            $where_fieldattach =   "(d.value LIKE ".$text .' OR '."a.title LIKE ".$text. ' OR ' . "a.introtext LIKE ".$text .")";
                      

                        }
                        //Extra fields
                        $whereextra = "";
                        
                        if(count($fieldsfilter)>0 && empty($tmplistids))
                        {
                            $whereextra = "";
                            $cont_field=0;
                           
                            $arrayfieldsid = explode(",",$fieldsfilter);
                            //$arrayfieldsid = $fieldsfilter;
                            foreach ($arrayfieldsid as $fieldsid)
                            {
                                 
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
                                
                                
                                //echo "<br />AAA".count($arrayfieldsid).">".($cont_field+1);
                                //if(!empty($tmpfieldid) && !empty( $tmp1[1])){
                                if(!empty($tmpfieldid) && !empty($valuefieldid)){
                                     
                                     //if(!empty($whereextra)) {    $whereextra .=  ' OR ';}
                                     if(!empty($whereextra)) 
                                     {    
                                         $whereextra .=  ' OR '; 
                                     }
                                      
                                      
                                     //LIKE
                                     if($rulesrray[$cont_field]=="LIKE"){
					//echo "<br>Ssssad sdasd1:::: ".$tmpfieldid." --- ".$valuefieldid." END<br>";
                                        $valuefieldid = $db->Quote('%'.$valuefieldid.'%', false);
					//echo "<br>Ssssad sdasd2:::: ".$tmpfieldid." --- ".$valuefieldid." END<br>";
                                        $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value LIKE '.$valuefieldid .')';
					//echo "<br>Ssssad sdasd3:::: ".$whereextra." END<br>";
                                     }
                                     //EQUAL
                                     if($rulesrray[$cont_field]=="EQUAL"){
                                        $valuefieldid = $db->Quote($db->getEscaped($valuefieldid, true), false);
                                        $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value = '.$valuefieldid .')';
                                     }
                                     //NOT EQUAL
                                     if($rulesrray[$cont_field]=="NOTEQUAL"){
                                        $valuefieldid = $db->Quote($db->getEscaped($valuefieldid, true), false);
                                        $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value != '.$valuefieldid .')';
                                     }
                                      //HIGHER
                                     if($rulesrray[$cont_field]=="HIGHER"){
                                        $valuefieldid = $db->Quote($db->getEscaped($valuefieldid, true), false);
                                        $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value > '.$valuefieldid .')';
                                     }
                                      //LOWER
                                     if($rulesrray[$cont_field]=="LOWER"){
                                        $valuefieldid = $db->Quote($db->getEscaped($valuefieldid, true), false);
                                        $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value < '.$valuefieldid .')';
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
                                }
                               
                                $cont_field++;
                            }
                            $whereextra .= "";
                        }
                        /*$whereextra = "";
                        if(!empty($tmpfieldid) && !empty($valuefieldid))
                        {
                             $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value LIKE '.$valuefieldid .')';
                        }
                           */             
                        if(!empty($where_fieldattach)) $where_fieldattach .= " AND ";
                        if(!empty($whereextra)) { $whereextra .= " AND ";   }
                       // $query->where( $whereextra. $where_fieldattach  .' a.state=1 AND c.published = 1 AND a.access IN ('.$groups.') ');
                        $query->where( $whereextra. $where_fieldattach  .' a.state=1 AND c.published = 1 ');
                        $query->where( "e.published=1");
                        
			// Filter by language
			if ($app->isSite() && $app->getLanguageFilter()) {
				$query->where('a.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
				$query->where('c.language in (' . $db->Quote($tag) . ',' . $db->Quote('*') . ')');
			}
                        
                        $tmpcat = explode(",",$categories);
                        
                        if(!empty($categories)){
                            $query->where('c.id in (' . $categories . ')');
                            
                        }
                          
                        if(!empty($tmplistids)){
                            $query->where('a.id in (' . $tmplistids . ')');
                            
                        }
                        
                        if(!empty($fieldOrderid) && is_numeric($fieldOrderid) && count($fieldsfilter)>0){
                            $query->where('d.fieldsid = ' . $fieldOrderid );
                            
                        }
                        
                       
                       $query->order($order);
		       //$query->group('a.id');
		       
		      //echo $query;
                        
                       return $query;
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


}
