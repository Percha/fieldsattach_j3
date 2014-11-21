<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
defined('JPATH_PLATFORM') or die;

jimport('joomla.installer.helper');



//this is intializer.php
defined('DS')?  null :define('DS',DIRECTORY_SEPARATOR);

 
/**
 * Script file of FIELDSATTACH component
 */
class com_fieldsattachInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         * <div id="system-message-container">
<div id="system-message">
<div class="alert alert-message"><a class="close" data-dismiss="alert">×</a>
<h4 class="alert-heading">Message</h4>
<div>
		<p>Installing component was successful.</p>
</div>
</div>
</div>
</div>
         */
        function install($parent) 
        {
                // $parent is the class calling this method
                echo '<div id="system-message-container">';
                $msgtext="";
                echo '<div id="system-message">
                <div style=" overflow:hidden; margin:8px 0 8px 0; padding:5px;" >
                <div style=" font-size:12px; margin:0px 0 0px 0; padding:5px; position:relative; float:right;"><div>Powered by Percha.com</div></div>
                <div style="float:left; margin:0 20px 20px 0;"><img src="http://www.fieldsattach.com/images/logo_fieldsattach_small.png" alt="fieldsattach.com" /></div>
                <div style="   margin:30px 0 8px 0; padding:5px; font-size:23px; color:#4892AB;">Thanks for install the Fieldsattach component.</div>
                </div>';
                
                //INSTALL THE PLUGINS *******************************************************************************
                $installer = new JInstaller();
                //$installer->_overwrite = true; 
                $pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fieldsattach'.DS.'extensions'.DS;
                $pkgs = array( 
		'input.zip'=>'Plugin fieldsattachment <strong>Input</strong>',
		'file.zip'=>'Plugin fieldsattachment <strong>File</strong>' ,
		'image.zip'=>'Plugin fieldsattachment <strong>image</strong>' ,
		'imagegallery.zip'=>'Plugin fieldsattachment <strong>imagegallery</strong>' ,
		'select.zip'=>'Plugin fieldsattachment <strong>select</strong>' , 
		'textarea.zip'=>'Plugin fieldsattachment <strong>textarea</strong>',
	  	'content_fieldsattachment.zip'=>'Plugin Content FieldsAttachment',
		'system_fieldsattachment.zip'=>'Plugin System FieldsAttachment',
		'advancedsearch_fieldsattachment.zip'=>'Plugin Advanced Search FieldsAttachment',
                'filterarticles.zip'=>'Plugin Advanced FILTER FieldsAttachment'
		
             );
                foreach( $pkgs as $pkg => $pkgname ):
                $package = JInstallerHelper::unpack( $pkg_path.$pkg );
                if( $installer->install( $package['dir'] ) )
                {
                     
                    $msgtext  .= '<div id="system-message-container"><div class="alert alert-message">'.$pkgname.' successfully installed.</div></div>';

                        //ACTIVE IT

                }
                else
                {
                     
                    $msgtext  .= '<div id="system-message-container"><div class="alert alert-message">ERROR: Could not install the $pkgname. Please install manually</div></div>';
                }
                
                //ACTIVE THE PLUGINS *******************************************************************************

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 1 WHERE  element = 'fieldsattachment'";
                $db->setQuery($sql);
                $db->query(); 

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 1 WHERE  element = 'fieldsattachmentadvanced'";
                $db->setQuery($sql);
                $db->query(); 

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 1 WHERE  folder = 'fieldsattachment'";
                $db->setQuery($sql);
                $db->query();


                //DESACTIVE OLD SEARCH
                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 0 WHERE  element = 'fieldsattachment' AND folder='search'";
                $db->setQuery($sql);
                $db->query();

                
                JInstallerHelper::cleanupInstall( $pkg_path.$pkg, $package['dir'] ); 
                endforeach; 
                
                //DELETE EXTENSIONS
                $pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fieldsattach'.DS.'extensions'.DS;

                 
                destroy_dir($pkg_path);
                $msgtext .= '<div id="system-message-container"><div class="alert alert-message">Clean install directory:  '.$pkg_path.'</div></div>' ;
                
                echo $msgtext;
                
                echo '</div>';
            
                //$parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        function uninstall($parent) 
        {
                // $parent is the class calling this method
                //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT-1') . '</p>';
                
                $msgtext ='';

                $msgtext ='<div style=" font-size:14px; ">UNINSTALL FieldsAttach in content article for Joomla</div>'; 
                $msgtext .= '<div style="border:1px dashed #cccccc; font-size:14px; margin:8px 0 8px 0; padding:5px;" >';
                $msgtext .= 'Component uninstall FieldsAttach success';
                $msgtext .= '</div>';

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 0 WHERE  folder = 'fieldsattachment'";
                $db->setQuery($sql);
                $db->query();

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 0 WHERE  element = 'fieldsattachment'";
                $db->setQuery($sql);
                $db->query(); 

                $db = JFactory::getDBO();
                $sql =  "UPDATE #__extensions  SET enabled = 0 WHERE  element = 'fieldsattachmentadvanced'";
                $db->setQuery($sql);
                $db->query(); 


                $db = JFactory::getDBO();

                $db->setQuery("select extension_id , name  from #__extensions where folder = 'fieldsattachment'");
                $plugins = $db->loadObjectList();
                if($plugins)
                {
                        foreach($plugins as $plugin)
                        {
                                $plugin_uninstaller = new JInstaller;
                                $msgtext .= '<div style="border:1px dashed #cccccc; font-size:14px; margin:8px 0 8px 0; padding:5px;" >';
                                if($plugin_uninstaller->uninstall('plugin', $plugin->extension_id))
                                    $msgtext .= 'Plugin '.$plugin->name.' uninstall fieldsattachment success <br />';
                                else
                                    $msgtext .=  'Plugin '.$plugin->name.' uninstall fieldsattachment failed<br />';
                                $msgtext .= '</div>'; 
                        }
                }




                //****************** 
                $db->setQuery("select extension_id , name  from #__extensions where element = 'fieldsattachment'");
                $plugins = $db->loadObjectList();
                if($plugins)
                {
                        foreach($plugins as $plugin)
                        {
                                $plugin_uninstaller = new JInstaller;
                                $msgtext .= '<div style="border:1px dashed #cccccc; font-size:14px; margin:8px 0 8px 0; padding:5px;" >';
                                if($plugin_uninstaller->uninstall('plugin', $plugin->extension_id))
                                    $msgtext .= 'Plugin '.$plugin->name.' uninstall fieldsattachment success <br />';
                                else
                                    $msgtext .=  'Plugin '.$plugin->name.' uninstall fieldsattachment failed<br />';
                                $msgtext .= '</div>'; 
                        }
                }

                //****************** 
                $db->setQuery("select extension_id , name  from #__extensions where element = 'fieldsattachmentadvanced'");
                $plugins = $db->loadObjectList();
                if($plugins)
                {
                        foreach($plugins as $plugin)
                        {
                                $plugin_uninstaller = new JInstaller;
                                $msgtext .= '<div style="border:1px dashed #cccccc; font-size:14px; margin:8px 0 8px 0; padding:5px;" >';
                                if($plugin_uninstaller->uninstall('plugin', $plugin->extension_id))
                                    $msgtext .= 'Plugin '.$plugin->name.' uninstall fieldsattachment success <br />';
                                else
                                    $msgtext .=  'Plugin '.$plugin->name.' uninstall fieldsattachment failed<br />';
                                $msgtext .= '</div>'; 
                        }
                }
                
                echo $msgtext;

        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
                $this->install($parent);
                // $parent is the class calling this method
                echo '<div id="system-message-container"><div class="alert alert-message">' . JText::sprintf('Fieldsattach Updated').'</div></div>';
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_FIELDSATTACH_PREFLIGHT_' . $type . '_TEXT1') . '</p>';
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_FIELDSATTACH_POSTFLIGHT_' . $type . '_TEXT2') . '</p>';
        }
}



function destroy_dir($dir) { 
    if (!is_dir($dir) || is_link($dir)) return unlink($dir); 
        foreach (scandir($dir) as $file) { 
            if ($file == '.' || $file == '..') continue; 
            if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) { 
                chmod($dir . DIRECTORY_SEPARATOR . $file, 0777); 
                if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) return false; 
            }; 
        } 
        return rmdir($dir); 
    } 
?>
