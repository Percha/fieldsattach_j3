<?php
/**
* @package		perchagoglemaps
* @copyright            Cristian Grañó
* @license		GNU/GPL, see LICENSE.php 
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

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
                
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();
 
		require_once JPATH_SITE.'/components/com_content/helpers/route.php';
		require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';
 

		$sContent		= $this->params->get('search_content',		1);
		$sArchived		= $this->params->get('search_archived',		1); 
                $limit			= $this->params->def('search_limit2',		50);
                //echo $limit;
               // $this->setState('limit', $app->getUserStateFromRequest('com_fieldsattach.limit', 'limit', $config->get('list_limit'), 'int'));
		
                //echo "STATE LIMIT::".$this->params->def('limit', 32);
                //$limit			= $this->params->def('search_limit',		50);

		$nullDate		= $db->getNullDate();
		$date = JFactory::getDate();
		//$now = $date->toMySQL();
                $now = $date->toISO8601();
                 
		 
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
                        
                        //All without filter
                        
                        /*if(count($fieldsfilter))
                        {
                            $whereextra = "";
                            $cont_field=0;
                            $arrayfieldsid = explode(",",$fieldsfilter);
                            foreach ($arrayfieldsid as $fieldsid)
                            {
                                $tmp1= explode("_",$fieldsid);
                                $tmpfieldid= $tmp1[0];
                                $valuefieldid="";
                                if(count($tmp1)>1) {
                                    $valuefieldid = $tmp1[1];
                                }
                                $valuefieldid = plgAdvancedsearchfieldsattachment::getValue($tmpfieldid, $valuefieldid);
                                //$where .= " ( d.fieldsid = ". $fieldsid. ' AND  d.value LIKE '.$text .')';
                                
                                $valuefieldid = $db->Quote('%'.$db->getEscaped($valuefieldid, true).'%', false);
                                $querytmp = plgAdvancedsearchfieldsattachment::getQuery($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit, $tmpfieldid, $valuefieldid);
                                //echo "".$query;
                                $query .= $querytmp ;
                                if($cont_field < count($arrayfieldsid)-1) $query .= " UNION  ";
                                $cont_field++;
                            }
                        }else{
                            //Without filter extrafield
                             $query = plgAdvancedsearchfieldsattachment::getQuery($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit);
                               
                        }*/
                        //echo "FILTER::".$fieldsfilter;
                        $query = plgAdvancedsearchfieldsattachment::getQuery($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit);
                      
                       // echo $query."<br><br>";
                                //echo "LIMIT:: ".$limit;
                        //$db->setQuery($query, 0, $limit);
                        $db->setQuery($query);
                        
                        $list = $db->loadObjectList();
                         
                        //TODO --> Repasar lista i escoger cuales son los IDS correctos 
                        $arrayfieldsid = explode(",",$fieldsfilter);
                        $numero_filtros = 0; 
                        //$numero_filtros = count($arrayfieldsid);
                        $cuantos = 0;
                        
                        foreach ($arrayfieldsid as $fieldsid)
                            {
                                $tmp1= explode("_",$fieldsid);
                                $tmpfieldid= $tmp1[0];
                                $valuefieldid="";
                                if(count($tmp1)>1) {
                                    $valuefieldid = $tmp1[1];
                                }  
                                //echo "<br />AAA".count($arrayfieldsid).">".($cont_field+1);
                                if(!empty($tmpfieldid) && !empty( $tmp1[1])){
                                    
                                    $numero_filtros++;
                                    
                                }
                                
                            }
                      //  echo "<br>Numero de filtros: ".$numero_filtros;
                        
                        
                        
                        $lstids = Array();
                        $newfields = Array();
                        $cont = 0;
                        $elidpasado = -1;
                        if(count($list)>0){
                            foreach($list as $unidad)
                            {
                                //PAsamos al siguiente ID
                            if($elidpasado != $unidad->id)
                            {
                                //echo "<br>Pasamos al siguiente ID:".$unidad->id." -- cuantos: ".$cuantos;
                                $elidpasado = $unidad->id;
                                $cuantos=0 ;
                            }else
                            {
                                //echo "<br> DE moemtno ".$cuantos;

                            }
                                //Comptrobamos si encontramos el id en nuestra tabla temporal
                                //echo "<br><br><br>EMPIEZA EL FOR DE ID:".$unidad->id;
                                $cuantos++;
                                $found=false;
                                foreach($lstids as $tmpids)
                                {
                                    if($tmpids == $unidad->id){ 
                                        $found=true;
                                        } 
                                }


                                //Si no encontrado miramos si es necesario añadirlo a la nueva talba de filtrados
                                if(!$found){
                                        //echo "<br>No tronbat ID:".$unidad->id." -- <br>";
                                        //echo "<br>Cuantos: ".$cuantos."  num filtros::".($numero_filtros)." -- <br>";

                                        if($cuantos == ($numero_filtros)){

                                            //Añadimos a $lstids
                                            $lstids[count($lstids)] = $unidad->id;
                                            //echo "<br>AÑADIMOSSS:".$unidad->id." NUMEOR DE VECES:".$cuantos;;
                                            $newfields[count($newfields)] = $unidad;

                                        }
                                        $cont++;

                                    }
                            // echo "Comparando ids: ".$elidpasado."!=". $unidad->id;

                            }
                        }
                        //echo "<br>LIST::: ".count($lstids);
                        
                        
                        $tmplistids = implode(",",$lstids);
                        
                        //echo "IDSARTICLE:: ".$tmplistids;
                        
                        $query->clear();
                        
                        
                        if(!empty($tmplistids) || ($numero_filtros==0)) 
                        {
                            $query = plgAdvancedsearchfieldsattachment::getQuery($text, $phrase, $order, $areas, $categories, $fieldsfilter, $limit, "", "", $tmplistids);
                      
                            $query->order($order);
                            $query->order("a.id"); 
                            $query->group('a.id');

                           // echo " <br>LIMIT:: ".$query;
                            $db->setQuery($query, 0, $limit);
                                    //$db->setQuery($query);

                            $list = $db->loadObjectList();
                        }else{ 
                            //Not exist only
                            $list =   Array();
                            
                            }


                        //echo $query;
                        
                        //$list = $newfields; 
                        //Realizar otro SQL con los id's directamente, así conseguimos el paginador 
                        
                        
                        /*
                        $numero_filtros = 0;
                         
                        $tmp = explode(" ", $searchword);
                        $numero_filtros = count($tmp);
                        $cuantos = 0;
                        
                       // echo "<br>Numero de filtros: ".$numero_filtros;
                        
                        $lstids = Array();
                        $newfields = Array();
                        $cont = 0;
                        $elidpasado = -1;
                        if(count($list)>0){
                            foreach($list as $unidad)
                            {
                                //PAsamos al siguiente ID
                            if($elidpasado != $unidad->id)
                            {
                                //echo "<br>Pasamos al siguiente ID:".$unidad->id." -- cuantos: ".$cuantos;
                                $elidpasado = $unidad->id;
                                $cuantos=0 ;
                            }else
                            {
                                //echo "<br> DE moemtno ".$cuantos;

                            }
                                //Comptrobamos si encontramos el id en nuestra tabla temporal
                                //echo "<br><br><br>EMPIEZA EL FOR DE ID:".$unidad->id;
                                $cuantos++;
                                $found=false;
                                foreach($lstids as $tmpids)
                                {
                                    if($tmpids == $unidad->id){ 
                                        $found=true;
                                        } 
                                }


                                //Si no encontrado miramos si es necesario añadirlo a la nueva talba de filtrados
                                if(!$found){
                                        //echo "<br>No tronbat ID:".$unidad->id." -- <br>";
                                        //echo "<br>Cuantos: ".$cuantos."  num filtros::".($numero_filtros)." -- <br>";

                                        if($cuantos == ($numero_filtros)){

                                            //Añadimos a $lstids
                                            $lstids[count($lstids)] = $unidad->id;
                                        // echo "<br>AÑADIMOSSS:".$unidad->id." NUMEOR DE VECES:".$cuantos;;
                                            $newfields[count($newfields)] = $unidad;

                                        }
                                        $cont++;

                                    }
                            // echo "Comparando ids: ".$elidpasado."!=". $unidad->id;

                            }
                        }
                       // echo "<br>LIST::: ".count($list);
                       // echo "<br>";
                         * 
                         */
                        
                        
                        //$list = $newfields;
                       /* $query->clear();
                        //Create new select with pagination
                        $query->select('a.id, a.title AS title, a.metadesc, a.metakey, a.created AS created, a.catid, a.publish_up , '
						.'CONCAT(a.introtext, a.fulltext) AS text, a.introtext, c.title AS section,  '
						.'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug  ');
						//.'"2" AS browsernav');
			$query->from('#__content AS a');
                        
			$query->innerJoin('#__categories AS c ON c.id=a.catid'); 
                        
                        $ids = "";$cont=0;
                        foreach($list as $unidad)
                        {
                            //Comptrobamos si encontramos el id en nuestra tabla temporal
                          //  echo "<br>ID:".$unidad->id;
                            $ids .= $unidad->id ;
                            if(($cont+1)<count($list)){ $ids .= ",";}
                            $cont++;
                        }
                        $query->where( 'a.id  IN ( '. $ids .')');
                         //$query->group('a.id');
			$query->order($order);
                        $query->order("a.id");
                        
                        $query->group('a.id');
                        
                        //echo $query;
                        $db->setQuery($query); 
			$list = $db->loadObjectList(); */
                         
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
                //echo $query;
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
        function getQuery($text, $phrase='', $order='', $areas=null, $categories =null, $fieldsfilter=null, $limit=50, $tmpfieldid="", $valuefieldid="", $tmplistids="" )
        {
            $db		= JFactory::getDbo();
            $app	= JFactory::getApplication();
            $query	= $db->getQuery(true);
            $tag = JFactory::getLanguage()->getTag();
            $query->select('a.id, a.title AS title, a.metadesc, a.metakey, a.created AS created, a.catid, a.publish_up , '
						.'CONCAT(a.introtext, a.fulltext) AS text, a.introtext, c.title AS section,  '
						.'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug, '
						.'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug  ');
						//.'"2" AS browsernav');
			$query->from('#__content AS a');

			$query->innerJoin('#__categories AS c ON c.id=a.catid');
                        $query->innerJoin('#__fieldsattach_values AS d ON d.articleid=a.id');
 
                        
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

                                                if(count($tmp)>($cont+1)) $where_fieldattach .=  ' OR ';
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
                        if(count($fieldsfilter) && empty($tmplistids))
                        {
                            $whereextra = "";
                            $cont_field=0;
                            //echo "<br />FIELD:: ".$cont_field;
                            $arrayfieldsid = explode(",",$fieldsfilter);
                            foreach ($arrayfieldsid as $fieldsid)
                            {
                                $tmp1= explode("_",$fieldsid);
                                $tmpfieldid= $tmp1[0];
                                $valuefieldid="";
                                if(count($tmp1)>1) {
                                    $valuefieldid = $tmp1[1];
                                }
                                $valuefieldid = plgAdvancedsearchfieldsattachment::getValue($tmpfieldid, $valuefieldid);
                                //$where .= " ( d.fieldsid = ". $fieldsid. ' AND  d.value LIKE '.$text .')';
                                
                                $valuefieldid = $db->Quote('%'.$db->getEscaped($valuefieldid, true).'%', false);
                                //echo "<br />AAA".count($arrayfieldsid).">".($cont_field+1);
                                if(!empty($tmpfieldid) && !empty( $tmp1[1])){
                                    
                                     if(!empty($whereextra)) {    $whereextra .=  ' OR ';}
                                     $whereextra .= " ( d.fieldsid = ". $tmpfieldid. ' AND  d.value LIKE '.$valuefieldid .')';
                                    
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
                        
                       


                        //$query->group('a.id');
			$query->order($order);
                        $query->order("a.id");

                        //  $where .= '  AND  a.publish_up >= "'.$year.'-'.$month.'-1" AND  a.publish_up <= "'.$year.'-'.$month.'-31"'  ;

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
                        
                        
                        
                        /*
                        $fields = $this->params->get('fields');
                        if(!empty($fields))
                        {
                            $query->where('d.fieldsid in (' . $fields . ')');

                        }*/ 
                         
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
