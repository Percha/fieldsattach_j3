<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');




/**
 * HTML View class for the Media component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since 1.0
 */
class fieldsattachViewImages extends JViewLegacy
{
	function display($tpl = null)
	{
		$config = JComponentHelper::getParams('com_media');
		$app	= JFactory::getApplication();
		$lang	= JFactory::getLanguage();
		$append = '';

		JHtml::_('behavior.framework', true);
		//JHtml::_('script', 'media/popup-imagemanager.js', true, true);
		JHtml::_('stylesheet', 'media/popup-imagemanager.css', array(), true);

		if ($lang->isRTL()) {
			JHtml::_('stylesheet', 'media/popup-imagemanager_rtl.css', array(), true);
		}
                
                $document = &JFactory::getDocument();
                
                 $directory = "administrator/";
                if ( JFactory::getApplication()->isAdmin()) {
                    $directory = "";
                } 
                if(JRequest::getVar("only")=="folder"){
                    $document->addScript($directory."components/com_fieldsattach/popup-imagemanager_folder.js");
                }else{
                    $document->addScript($directory."components/com_fieldsattach/popup-imagemanager.js");
               
                }
                
                //ADD LANGUAGE
                $lang =& JFactory::getLanguage();
                $extension = 'com_media';
                $base_dir = JPATH_SITE;
                $language_tag = JRequest::getVar("lang");
                $reload = true;
                $lang->load($extension, $base_dir, $language_tag, $reload);

                //FILEROOT
		/*$path = "file_path";

                $view = JRequest::getCmd('view');
                if (substr(strtolower($view), 0, 6) == "images" || $popup_upload == 1) {
                        $path = "image_path";
                }


                define('COM_MEDIA_BASE',	JPATH_ROOT.'/'.$config->get($path, 'images'));
                define('COM_MEDIA_BASEURL', JURI::root().$config->get($path, 'images'));
                */
                
               // $sitepath = JPATH_ADMINISTRATOR ;   
               // JLoader::register('MediaModelManager',  $sitepath.DS.'components/com_media/models/manager.php');

		/*
		 * Display form for FTP credentials?
		 * Don't set them here, as there are other functions called before this one if there is any file write operation
		 */
		$ftp = !JClientHelper::hasCredentials('ftp');

		$this->session = JFactory::getSession();
		$this->config = $config;
		$this->state = $this->get('state');
		$this->folderList = $this->get('folderList');
                //$this->folderList = MediaModelManager::getFolderList(COM_MEDIA_BASE);
                //echo "FOLDEER:".MediaModelManager::getFolderList(COM_MEDIA_BASE) ;
		$this->require_ftp = $ftp;

		parent::display($tpl);
	}
}
