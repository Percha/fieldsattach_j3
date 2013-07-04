<?php
/**
 * @version		$Id: fieldattachgroups.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
 

/**
 * fieldsattachs Model
 */
class fieldsattachModelfieldsattachgroups extends JModelList
{
         

        /**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
            parent::__construct();
        }
        /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
                // Initialise variables.
		 $app = JFactory::getApplication('administrator');

                $for = $this->getUserStateFromRequest($this->context.'.filter.for', 'filter_for', -1, 'int');
                $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', -1, 'int');
                $category_id = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null, 'int');

                //$language = JRequest::getVar("filter_language");
                //  $this->setState('filter.group_id', $groupId);
                $language = JRequest::getVar("filter_language");
                $this->setState('filter.language', $language);
                $this->setState('filter.for', $for);
                $this->setState('filter.published', $published);
                $this->setState('filter.category_id', $category_id);
                
                // List state information.
		parent::populateState('a.title', 'asc');
        }

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
                $query2 = $db->getQuery(true);

                $language = $this->getState('filter.language');
                $for = $this->getState('filter.for');
                $published = $this->getState('filter.published');
                $category_id = $this->getState('filter.category_id'); 
                //JLoader::register('fieldattachHelper', JURI::base().'components/com_fieldsattach/helpers/fieldsattach.php');

                $this->first = true;
                $this->recursivecat($category_id);
               // echo "<br><br>rec".$this->str;

		// Select some fields
		$query->select('*');

		// From the hello table
		$query->from('#__fieldsattach_groups');
                if(!empty($language) AND ($language != "*")){$query->where(' ( language="'.$language.'" OR language="*")' ); }
		if( ($for >  -1 )){$query->where('  group_for="'.$for.'" ' ); }
		if( ($published >=  0 )){$query->where('  published="'.$published.'" ' ); }
                if( ($category_id >=  0 && !empty($this->str))){$query->where('  catid IN ('.$this->str.') ' ); }
                $query->where('  recursive = 1' );
                //$query->order(' ordering');


                $query2->select('*');
                $query2->from('#__fieldsattach_groups');
                if(!empty($language) AND ($language != "*")){$query2->where(' ( language="'.$language.'" OR language="*")' ); }
		if( ($for >  -1 )){$query2->where('  group_for="'.$for.'" ' ); }
		if( ($published >=  0 )){$query2->where('  published="'.$published.'" ' ); }
                if( ($category_id >  0 )){$query2->where('  catid IN ('.$category_id.') ' ); }
                $query2->order(' ordering');



                 if($category_id >  0 )
                 {
                    $total_query =  $query ." UNION ".$query2;
                 }else
                 {
                     $total_query = $query2;
                 }

                
                return  $total_query;

	}

         /**
	* recursive function
	*
	* @access	public
	* @since	1.5
	*/
        /*private function recursivecat($catid)
        {
                if(!empty($this->str)) $this->str .=  ",";
                $this->str .= $catid ;
                //echo "SUMO:".$str."<br>";
                $db	= & JFactory::getDBO();
                //$query = 'SELECT parent_id FROM #__categories as a WHERE a.id='.$catid   ;
                $query = 'SELECT id FROM #__categories as a WHERE a.parent_id='.$catid   ;
                //echo $query."<br>";
                $db->setQuery( $query );
                $tmp = $db->loadObjectList();
                if(count($tmp)>0){
                    foreach ($tmp as $obj)
                      $this->recursivecat($obj->id);
                }
                
        } */

        private function recursivecat($catid)
        {
		 if(!empty($catid)){
		        if(!empty($this->str)) $this->str .=  ",";
		        $this->str .= $catid ;
		        //echo "SUMO:".$str."<br>";
		        $db	= & JFactory::getDBO();
		        $query = 'SELECT a.parent_id  FROM #__categories as a WHERE a.id='.$catid   ;

		        
		        //echo $query."<br>";
		        $db->setQuery( $query );
		        $tmp = $db->loadObject();
		        if(!empty($tmp)){
		            if($tmp->parent_id>0) {
		                  $this->recursivecat($tmp->parent_id);
		            }
		        }
		}

        }

	
	 
        
}
