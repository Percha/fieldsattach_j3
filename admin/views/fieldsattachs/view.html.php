<?php
/**
 * @version		$Id: view.html.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * fieldsattachs View
 */
class fieldsattachViewfieldsattachs extends JViewLegacy
{
	/**
	 * fieldsattachs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		
		//Version
		$this->version=""; 
		$xml=JFactory::getXML(JPATH_COMPONENT.DS.'fieldsattach.xml');
		$this->version =(string)$xml->version;

		// Set the toolbar
		$this->addToolBar();
		
		//Add script
		$this->jqueryscript();

		// Display the template
		parent::display($tpl);

		// Set the document
		//$this->setDocument();
	}
	
	/**
	 * Add script
	 */
	protected function jqueryscript()
	{
		$script='var url = "http://fieldsattach.com/update/control.php"; // the script where you handle the form input.
		jQuery(document).ready(function (){ 
		    jQuery.ajax({
			   type: "POST",
			   url: url,
			   data: jQuery("#checkupdatesForm").serialize(), 
			   success: function(data)
			   {
				var obj = jQuery.parseJSON( data);
			       jQuery("#checkupdates").html(""+obj.msg );
			   },
				error: function (xhr, ajaxOptions, thrownError) {
				  
				   jQuery("#checkupdates").html("");
				}
			 });
			 });';
		 
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $script );
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = fieldsattachHelper::getActions();
		JToolBarHelper::title(JText::_('COM_FIELDATTACH_MANAGER_FIELDATTACHS'), 'cpanel');
		if ($canDo->get('core.create')) 
		{
			//JToolBarHelper::addNew('fieldsattach.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			//JToolBarHelper::editList('fieldsattach.edit', 'JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.delete')) 
		{
			//JToolBarHelper::deleteList('', 'fieldsattach.delete', 'JTOOLBAR_DELETE');
		}
		if ($canDo->get('core.admin')) 
		{
			//JToolBarHelper::divider();
			JToolBarHelper::preferences('com_fieldsattach');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		//$document = JFactory::getDocument();
		//$document->setTitle(JText::_('COM_FIELDSATTACH_ADMINISTRATION'));
	}
}
