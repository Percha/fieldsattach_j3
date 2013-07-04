<?php
/**
 * @version		$Id: listgroupp.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * fieldsattach Form Field class for the fieldsattach component
 */
class JFormFieldfolder extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'folder';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getInput() 
	{
                
            $str = '<input id="'.$this->id.'" name="'.$this->id.'" type="text" value=""/>';
            $str .= '<div class="button2-left">  
                        <div class="blank">
                                <a class="modal modal-button" title="Select File" href="'.$directory.'index.php?option=com_fieldsattach&amp;view=images&amp;only=folder&amp;tmpl=component&amp;asset=140&amp;author=&amp;fieldid='.$this->id.'&amp;folder=&amp;functionName=mountlink_'.$fieldsid.'" rel="{handler: \'iframe\', size: {x: 800, y: 303}}">
                        Select Folder</a>
                        </div>
                        </div>  
                        <script> function jInsertFieldValue(txt, field){ $(field).value= "/"+ txt ;  window.SqueezeBox.close();}</script> '; 
              
                 
                  
            return $str;
	}
}
