<?php
/**
 * @version		$Id: default.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$sitepath = JPATH_SITE;
JLoader::register('fieldsattachHelper',   $sitepath.DS.'administrator/components/com_fieldsattach/helpers/fieldsattach.php');
// load tooltip behavior
JHtml::_('behavior.tooltip');

JPluginHelper::importPlugin('fieldsattachment'); // very important
//renderInput
plgfieldsattachment_imagegallery::construct1();
//$articleid, $fieldsid, $value, $extras=null
$articleid = JRequest::getVar("catid");
$fieldsid = JRequest::getVar("fieldsid");
//echo plgfieldsattachment_imagegallery::renderInput($articleid, $fieldsid, null);
//echo fieldsattachHelper::getGallery($articleid, $fieldsid);


echo plgfieldsattachment_imagegallery::getGallery1($articleid, $fieldsid);

?>
	 
 
