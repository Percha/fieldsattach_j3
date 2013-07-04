<?php
/**
 * @version		$Id: view.html.php 21593 2011-06-21 02:45:51Z cristian $
 * @package		Joomla.Site
 * @subpackage	com_fieldsattach
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the search component
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_search
 * @since 1.0
 * TODO WRITE JS AJAX 
 */
class FieldsattachViewFields extends JView
{
	function display($tpl = null)
	{

             //***********************************************************************************************
             //Where we are  ****************  ****************  ****************
             //***********************************************************************************************
            /*
             $task = JRequest::getVar('task');
             $option= JRequest::getVar('option');
             $id= JRequest::getVar('id', JRequest::getVar('a_id'));

             $view= JRequest::getVar('view');
             $layout= JRequest::getVar('layout');

             $catid= JRequest::getVar('catid', 0);

             $fontend = false;
             if( $option=='com_content' && $user->get('id')>0 &&  $view == 'form' &&  $layout == 'edit'  ) $fontend = true;

             $backend = false;
             if( $option=='com_content' && !empty($pos) &&  $layout == 'edit') $backend = true;

             $backendcategory = false;
             if ((JRequest::getVar('option')=='com_categories' && JRequest::getVar('view')=="category"  && JRequest::getVar('extension')=="com_content"  )){
                 $backendcategory = true;
                 $backend=true;
             }

             if(($fontend)&&(empty($id))){
                 //echo "el id".$id."<br>";
                 $id = JRequest::getVar( 'a_id');
             }

             $backend = true;

            //***********************************************************************************************
            //create array of fields  ****************  ****************  ****************
            //***********************************************************************************************
            $fields = array();
            if($backendcategory){
                if(!empty($id)){
                    $fields_tmp0 = fieldsattachHelper::getfieldsForAllCategory($id);
                    $fields = $this->getfieldsCategory($id);
                    $fields = array_merge($fields_tmp0, $fields);
                }

            }else{

                $fields_tmp0 = fieldsattachHelper::getfieldsForAll($id);

                //$fields_tmp1 = $this->getfields($id);
                $fields_tmp1 = fieldsattachHelper::getfields($id,$catid);
                $fields_tmp1 = array_merge($fields_tmp0, $fields_tmp1);
                $fields_tmp2 = fieldsattachHelper::getfieldsForArticlesid($id, $fields_tmp1);
                $fields = array_merge($fields_tmp1, $fields_tmp2);
                //$fields = $fields_tmp0;
            }

            //***********************************************************************************************
            //create HTML  with new extra fields  ****************  ****************  ****************
            //***********************************************************************************************

            if(count($fields)>0){
                $str = fieldsattachHelper::getinputfields($id, $fields, $backend, $fontend, $backendcategory, &$body);
                $str_options = fieldsattachHelper::getrightfields($fields);
            }

            $this->assignRef('str',  $str);

            $catid = JRequest::getVar("catid", -1);
            $this->assignRef('catid',  $catid);



            //JPluginHelper::importPlugin('system'); // very important
            //array (& $item->text, & $item->params, $limitstart) 

	    parent::display($tpl);*/
	}
}
