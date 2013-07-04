<?php
/**
 * @version		$Id: search.php 22338 2011-11-04 17:24:53Z github_bot $
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');


$params = JComponentHelper::getParams('com_media');


// Require the base controller
require_once JPATH_COMPONENT.'/controller.php';

// Set the path definitions
define('COM_MEDIA_BASE',	JPATH_ROOT.'/'.$params->get('image_path', 'images'));
define('COM_MEDIA_BASEURL', JURI::root().'/'.$params->get('image_path', 'images'));



// Make sure the user is authorized to view this page
$user	= JFactory::getUser();
$app	= JFactory::getApplication();
$cmd	= JRequest::getCmd('view', null);
 
// We have a defined controller/task pair -- lets split them out
//list($controllerName, $task) = explode('.', $cmd);

//$controllerName1="fieldsattach".$controllerName; 


// Define the controller name and path
//$controllerName1	= strtolower($controllerName1);
//$controllerPath	= JPATH_COMPONENT_ADMINISTRATOR.'/controllers/'.$controllerName1.'.php';


$controllerPath =  JPATH_COMPONENT_ADMINISTRATOR.'/controller.php';
$controller ="";
// If the controller file path exists, include it ... else lets die with a 500 error
//if (file_exists($controllerPath)) {
        //require_once $controllerPath;
        

        // Set the name for the controller and instantiate it
        //$controllerClass = 'MediaController'.ucfirst($controllerName);
        $vName = JRequest::getVar("view");
        $task = JRequest::getVar("task");
        if($vName == "images" || $vName == "imagesList" || $vName == "search" || $vName == "advancedsearch" || $vName == "fieldsattachimagesajax" || $task == "save" || $task == "delete"  )
        {
            $controllerClass = "fieldsattachController";
            
        }else{   
            $controllerClass = "fieldsattachController";
            //$controllerClass = "fieldsattachController";
            // We have a defined controller/task pair -- lets split them out
            list($controllerName, $task) = explode('.', $cmd);

            // Define the controller name and path
            $controllerName	= strtolower($controllerName);
           // echo "<br>Control nam:".$controllerName;
            $controllerPath	= JPATH_COMPONENT_ADMINISTRATOR.'/controllers/'.$controllerName.'.php';

            //echo "<br>CONTROLPATH".$controllerPath;
            // If the controller file path exists, include it ... else lets die with a 500 error
            if (file_exists($controllerPath)) {
                    require_once $controllerPath; 
            }
            else {
                    //JError::raiseError(500, JText::_('JERROR_INVALID_CONTROLLER'));
            }
            
            $controllerClass = "fieldsattachController".$controllerName;
            //echo "<br>viewname:".$vName;
            //echo "<br>control name:  fieldsattachController".$controllerName;
        }
        
        

        if (class_exists($controllerClass)) { 
                $controller = new $controllerClass(); 
                 
                 // Set the model and view paths to the administrator folders
                //$controller->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.'/views');
                $controller->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
        
        
        }
        else {
                JError::raiseError(500, JText::_('JERROR_INVALID_CONTROLLER_CLASS'));
        }
        
        $controllerClass = "fieldsattachController";
        $controller = JControllerLegacy::getInstance('Fieldsattach');
        //$controller->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.'/views');
        //$controller->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.'/models');
        
         
        //echo "Controllers ADD view: ".JPATH_COMPONENT_ADMINISTRATOR.'/views';

/*}
else {
        //JError::raiseError(500, JText::_('JERROR_INVALID_CONTROLLER'));

        // Create the controller
        $controller = JController::getInstance('Fieldsattach');
         

}*/



// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
