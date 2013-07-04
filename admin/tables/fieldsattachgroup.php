<?php
/**
 * @version		$Id: fieldattachgroup.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Hello Table class
 */
class fieldsattachTablefieldsattachgroup extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__fieldsattach_groups', 'id', $db);
               
	}
	/**
	 * Overloaded bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '') 
	{
		if (isset($array['params']) && is_array($array['params'])) 
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}

                if (is_array($array['catid'])){
                $array['catid'] = implode(',',$array['catid']);
                }
                 
		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded load function
	 *
	 * @param       int $pk primary key
	 * @param       boolean $reset reset data
	 * @return      boolean
	 * @see JTable:load
	 */
	public function load($pk = null, $reset = true) 
	{ 
		if (parent::load($pk, $reset)) 
		{
			// Convert the params field to a registry.
			$params = new JRegistry;

                       // $this->catid = explode(',', $this->catid);
			//$params->loadJSON($this->params);
			//$this->params = $params;
			return true;
		}
		else
		{
			return false;
		}
	}

        /**
	 * method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	function store($updateNulls = false)
	{
	      //echo "<br>TABLE STORE:: ".$this->id;
             // echo "<br>TABLE STORE:: ".$this->_getAssetName();
             // Attempt to store the user data.
            // JError::raiseWarning( 100,  "DELETE  task:".JRequest::getVar("task")  );
	     return parent::store($updateNulls);
	}
	 

        
         /**
	 * method to store a row
	 *
	 * @param boolean $updateNulls True to update fields even if they are null.
	 */
	public function publish($cid, $valor)
	{
	     //echo "<br>TABLE STORE:: ".;
            $db	= & JFactory::getDBO();
             
            $query = 'UPDATE  #__fieldsattach_groups SET published="'.$valor.'" WHERE id IN ('.implode($cid,",").')' ;
            //echo $query;
            $db->setQuery($query);
            $db->query();

                            
             // echo "<br>TABLE STORE:: ".$this->_getAssetName();
             // Attempt to store the user data.
            
	      
	}
        
	
	
	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array    $idArray    An array of primary key ids.
	 * @param   integer  $lft_array  The lft value
	 *
	 * @return  boolean  False on failure or error, True otherwise
	 *
	 * @since   1.6
	*/
	public function saveorder($idArray = null, $lft_array = null)
	{
		 
		$this->reorder();
		

		return true;
	}

         
}
