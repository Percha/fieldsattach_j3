<?php
/**
 * @version		$Id: fieldattachunidad.php 15 2011-09-02 18:37:15Z cristian $
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
class fieldsattachTablefieldsattachunidad extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__fieldsattach', 'id', $db);
               
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
			/*$params = new JRegistry;
			$params->loadJSON($this->params);
			$this->params = $params;
			return true;*/
                        
                        
			// Convert the params field to a registry.
			$params = new JRegistry;
                        
                        //Joomla2.5
			//$params->loadJSON($this->params);
                        //joomla3
                        $params->loadString($this->params);
			$this->params = $params;
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
	     return parent::store($updateNulls);
	}
	 

	 /**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param mixed $pks An optional array of primary key values to update.
	 * If not set the instance property value is used.
	 * @param integer $state The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param integer $userId The user id of the user performing the operation.
	 *
	 * @return boolean True on success; false if $pks is empty.
	 *
	 * @link http://docs.joomla.org/JTable/publish
	 * @since 11.1
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
	     //echo "<br>TABLE STORE:: ".;
            $db	= & JFactory::getDBO();
             
            $query = 'UPDATE #__fieldsattach SET published="'.$state.'" WHERE id IN ('.implode($pks,",").')' ;
            //echo $query;
            $db->setQuery($query);
            $db->query();

                            
              echo "<br>TABLE STORE:: ".$this->_getAssetName();
             // Attempt to store the user data.
	}
        

         
}
