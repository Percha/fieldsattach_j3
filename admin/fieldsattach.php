<?php

/**
 * @version		$Id: fieldsattach.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


//this is intializer.php
defined('DS')?  null :define('DS',DIRECTORY_SEPARATOR);

$params = JComponentHelper::getParams('com_media');
define('COM_MEDIA_BASE',	JPATH_ROOT.'/'.$params->get($path, 'images'));
define('COM_MEDIA_BASEURL', JURI::root().$params->get($path, 'images'));

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fieldsattach')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(JRequest::getVar("view") == "fieldsattachimages" || JRequest::getVar("view") == "fieldsattachimage") JRequest::setVar('tmpl','component');
// require helper file
JLoader::register('fieldsattachHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'fieldsattach.php');
 

// import joomla controller library
jimport('joomla.application.component.controller');
  

// Get an instance of the controller prefixed by fieldsattach
$controller = JControllerLegacy::getInstance('fieldsattach'); 
 
// K2 - compatibility *****************
$tmp = JRequest::getVar('task'); 
$tmp = explode(".",$tmp); $task = $tmp[0];
if(count($tmp)>1) $task = $tmp[1];
 
 
// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
 
