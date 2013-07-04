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
class fieldsattachViewfieldsattachbackup extends JViewLegacy
{
	/**
	 * fieldsattachs view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		 
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
		JToolBarHelper::title(JText::_('COM_FIELDATTACH_MANAGER_FIELDATTACHS'), 'cpanel');
		 
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
