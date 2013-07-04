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
class fieldsattachViewfieldsattachgroups extends JViewLegacy
{
        protected $state;
        protected $pagination;
        protected $items;
	/**
	 * fieldsattachs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$this->items                  =   $this->get('Items');
		$this->pagination             =   $this->get('Pagination');
                $this->state		=   $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		} 
		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		//$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		$canDo = fieldsattachHelper::getActions();
		JToolBarHelper::title(JText::_('COM_FIELDATTACH_MANAGER_FIELDATTACHGROUPS'), 'categories');
		if ($canDo->get('core.create')) 
		{
			JToolBarHelper::addNew('fieldsattachgroup.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			JToolBarHelper::editList('fieldsattachgroup.edit', 'JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.delete')) 
		{
			JToolBarHelper::deleteList('', 'fieldsattachgroup.delete', 'JTOOLBAR_DELETE');
		}
                if ($canDo->get('core.admin')) {
                        JToolBarHelper::divider();
			JToolBarHelper::custom('fieldsattachgroup.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('fieldsattachgroup.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true); 
		}
		if ($canDo->get('core.admin')) 
		{
			JToolBarHelper::divider();
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
	
	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING')
			
		);
	}
}
