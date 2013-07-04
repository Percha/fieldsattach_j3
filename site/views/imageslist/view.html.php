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
class fieldsattachViewImagesList extends JViewLegacy
{
	function display($tpl = null)
	{
		// Do not allow cache
		JResponse::allowCache(false);

		$app = JFactory::getApplication();

		$lang	= JFactory::getLanguage();

		JHtml::_('stylesheet', 'media/popup-imagelist.css', array(), true);
		if ($lang->isRTL()) :
			JHtml::_('stylesheet', 'media/popup-imagelist_rtl.css', array(), true);
		endif;
                
                $css = '.item{position:relative; width:80px; height:80px; overflow:hidden;}
                    .item .titledoc{position:absolute;width:80px; bottom:0; left:0; padding:1px 0; background-color:#eee;}';
                $document = &JFactory::getDocument();
                //$document->addStyleSheet(   JURI::base().'../plugins/system/fieldsattachment/js/style.css' );
                $document->addStyleDeclaration($css)     ;

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("var ImageManager = window.parent.ImageManager;");

		$images = $this->get('images');
		$folders = $this->get('folders');
		$state = $this->get('state');
                $docs = $this->get('documents');

		$this->assign('baseURL', COM_MEDIA_BASEURL);
		$this->assignRef('images', $images);
		$this->assignRef('folders', $folders);
                $this->assignRef('docs', $docs);
		$this->assignRef('state', $state);

		parent::display($tpl);
	}


	function setFolder($index = 0)
	{
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}

	function setImage($index = 0)
	{
		if (isset($this->images[$index])) {
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}
        
        function setDocument($index = 0)
	{
		if (isset($this->docs[$index])) {
			$this->_tmp_doc = &$this->docs[$index];
		} else {
			$this->_tmp_doc = new JObject;
		}
	}
}
