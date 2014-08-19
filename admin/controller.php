<?php

/**
 * @version		$Id: controller.php 15 2011-09-02 18:37:15Z cristian $
 * @package		fieldsattach
 * @subpackage		Components
 * @copyright		Copyright (C) 2011 - 2020 Open Source Cristian Grañó, Inc. All rights reserved.
 * @author		Cristian Grañó
 * @link		http://joomlacode.org/gf/project/fieldsattach_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller'); 
jimport('joomla.error.error' );
jimport('joomla.log.log');

/**
 * General Controller of fieldsattach component
 */
class fieldsattachController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) 
	{
		
		 
		
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'fieldsattachs'));

                $view	= JRequest::getCmd('view', 'default');
		$layout = JRequest::getCmd('layout', 'default'); 
		$task	= JRequest::getVar('task', 'default');
                 
		$session = JFactory::getSession();
		$fieldsattachid =  JRequest::getVar('fieldsattachid', '');  
		if(!empty($fieldsattachid)) $session->set('fieldsattachid',$fieldsattachid);
		
		//echo "FIELDS::".$fieldsattachid;

                
                
                //MEDIA
                $vName = JRequest::getCmd('view', 'media');
                if($view ==  'images'){ 
				$mName = 'manager';
                }

                if($view ==  'imagesList'){
				$mName = 'list'; 

                }
                
                if(($view ==  'imagesList')||($view ==  'images')){
                    $document = JFactory::getDocument();
                    $vType		= $document->getType();
                    // Get/Create the view
                    $view = $this->getView($vName, $vType);
                    

                    // Get/Create the model
                    if ($model = $this->getModel($mName)) {
                            
                            // Push the model into the view (as default)
                            $view->setModel($model, true);
                    }

                    // Set the layout
                    $view->setLayout($layout);

                    // Display the view
                    $view->display();
                }else{
                
                    //NORMAL

                    //echo $view;
                    // echo '<br>'.$task;
                    if($task == "delete"){
                            $this->deleteimage();
                    }else{ 
                    //	if ($task == 'default')$this->updateimage();
                            // call parent behavior
				if($task == "fieldsattachimagesorderajax")
				{
						// Add a message.
						JLog::add('Fieldstattach', JLog::WARNING, 'fieldsattachimagesorderajax');
				}else{
						parent::display($cachable);
				}
                    }
		    
		  

                    // Load the submenu.
                    fieldsattachHelper::addSubmenu(JRequest::getCmd('view', 'fieldsattach'));
                }
            
	}
        
        function ftpValidate()
	{
		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');
	}
	
	function deleteimage()
	{
		 
		$model = $this->getModel( "fieldsattachimage" );
                $model->deleteone();
		
		$fieldsid = JRequest::getVar('fieldsid',0);
		
		echo "
			<script>
				//window.parent.document.location.reload(true); 
				window.parent.  update_gallery".$fieldsid."(); 
			 	window.parent.SqueezeBox.close();
			 	
				//window.parent.Joomla.submitbutton('article.apply'); 
            	//window.parent.document.getElementById('sbox-window').close();
            	//
            </script> ";
		$model = $this->getModel( "fieldsattachimage" );
                $model->deleteone();
	}
	
	function updateimage()
	{
		 /*
		
		$fieldsattachid =  JRequest::getVar('fieldsattachid', '');    
		echo "
			<script> 
				//window.parent.  update_gallery".$fieldsattachid."(); 
				
				//upadate_all
				//window.parent.update_gallery2();
				//window.parent.update_gallery();
			 	 //alert('fieldsattachid');
            </script> "; */
	}
	
        function export2(){
            
           /*
            */
            
            
            global $mainframe;

                    ## Make DB connections
                    $db    = JFactory::getDBO();

                    $sql = 'SELECT * FROM #__fieldsattach';

                    $db->setQuery($sql);
                    $rows = $db->loadAssocList();

                    ## If the query doesn't work..
                    if (!$db->query() ){
                        echo "<script>alert('Please report your problem.');
                        window.history.go(-1);</script>\n";       
                    }   

                    ## Empty data vars
                    $data = "" ;
                    ## We need tabbed data
                    $sep = "\t"; 

                    $fields = (array_keys($rows[0]));

                    ## Count all fields(will be the collumns
                    $columns = count($fields);
                    ## Put the name of all fields to $out.  
                    for ($i = 0; $i < $columns; $i++) {
                        $data .= $fields[$i].$sep;
                    }

                    $data .= "\n";

                    ## Counting rows and push them into a for loop
                    for($k=0; $k < count( $rows ); $k++) {
                        $row = $rows[$k];
                        $line = '';

                        ## Now replace several things for MS Excel
                        foreach ($row as $value) {
                        $value = str_replace('"', '""', $value);
                        $line .= '"' . $value . '"' . "\t";
                        }
                        $data .= trim($line)."\n";
                    }

                    $data = str_replace("\r","",$data);

                    ## If count rows is nothing show o records.
                    if (count( $rows ) == 0) {
                        $data .= "\n(0) Records Found!\n";
                    }

                    ## Push the report now!
                    $this->name = 'export-fieldsattach';
                    //header("Content-type: application/octet-stream");
                    header('Content-type: application/csv');
                    header("Content-Disposition: attachment; filename=".$this->name.".csv");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    header("Lacation: excel.htm?id=yes");
                    print $data ;
                    die();   
                    
            //parent::display(); 
            
            
        }
        
        
        /* backup the db OR just a table */
        function export()
        { 
            
            //DB Connection
            $Config = new JConfig();
            $host = $Config->host ;
            $user = $Config->user ;
            $pass = $Config->password ;
            $name = $Config->db ;
            $prefix = $Config->dbprefix;
            $tables = $prefix.'fieldsattach,'.$prefix.'fieldsattach_categories_values,'.$prefix.'fieldsattach_groups, '.$prefix.'fieldsattach_images, '.$prefix.'fieldsattach_values';
            $return="";

            $link = mysql_connect($host,$user,$pass);
            mysql_select_db($name,$link);

            //get all of the tables
            if($tables == '*')
            {
                $tables = array();
                $result = mysql_query('SHOW TABLES');
                while($row = mysql_fetch_row($result))
                {
                $tables[] = $row[0];
                }
            }
            else
            {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

            //cycle through
            foreach($tables as $table)
            {
                $result = mysql_query('SELECT * FROM '.$table);
                //echo 'SELECT * FROM '.$table;
                $num_fields=0;
                if(count($result)>0){
                $num_fields = mysql_num_fields($result); 
                }
                //DROP TABLE IF EXISTS `#__fieldsattach`;
                $return.= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                $createsqtring = str_replace(array('\r\n', PHP_EOL), '', $row2[1]);  
                //$return.= "\n\n".$row2[1].";\n\n";
                $return.= "\n\n".$createsqtring.";\n\n";

                
                for ($i = 0; $i < $num_fields; $i++) 
                {
                while($row = mysql_fetch_row($result))
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j<$num_fields; $j++) 
                    {
                    // echo $row[$j];
                    $row[$j] = addslashes($row[$j]);
                    //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
                    $row[$j] = preg_replace("/\n/","/\\n/",$row[$j]);

                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                    //$return = str_replace(array('\r\n', PHP_EOL), '', $return);  
                    //$return= $return."\n";
                }
                }
                $return.="\n\n\n";
            }

            //save file
            $app = JFactory::getApplication();
            $path = JPATH_SITE.DS.'images'.DS;
            $filename = 'db-backup-'.time().'-'.(md5(implode(',',$tables)));
            $handle = fopen($path.$filename.'.sql','w+');
            fwrite($handle,$return);
            fclose($handle);
            
            $files_to_zip = array(
            $path.$filename.'.sql'
            );
            //if true, good; if false, zip creation failed
            $result = $this->create_zip($files_to_zip, $path.$filename.'.zip');
            $app->enqueueMessage( JTEXT::_("EXPORT OK") . '<br /><br /> Download: <a href="../images/'.$filename.'.zip">images/'.$filename.'.zip</a>' )   ;
            unlink($path.$filename.'.sql');
            
             // Load the submenu.
		fieldsattachHelper::addSubmenu(JRequest::getCmd('view', 'fieldsattach'));
            parent::display(); 
        }
        
        /* backup the db OR just a table */
        function import()
        { 
		$app = JFactory::getApplication();
		//DB Connection
		$Config = new JConfig();
		$host = $Config->host ;
		$user = $Config->user ;
		$pass = $Config->password ;
		$name = $Config->db ;
		$prefix = $Config->dbprefix;
		$return="";
    
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
      
		 
		if ($_FILES["file"]["error"] > 0)
		{
		    $str =  "Error: " . $_FILES["file"]["error"] . "<br />";
		    $app->enqueueMessage( $str  )   ;
		}
		else
		{
		    $str = "<br /><br />Upload: " . $_FILES["file"]["name"] . "<br />";
		    $str .=  "Type: " . $_FILES["file"]["type"] . "<br />";
		    $str .=  "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		    $str .=  "Stored in: " . $_FILES["file"]["tmp_name"]. " <br /><br />";
		    
		    $app->enqueueMessage( $str  )   ;
		    // let's pretend that connection to server is established
		    // and database chosen...
		    /*$sql = explode(';#%%', file_get_contents ($_FILES["file"]["tmp_name"]));
		    $n = count ($sql) - 1;
		    for ($i = 0; $i < $n; $i++) {
			$query = $sql[$i];
			echo $query."<br>";
			$result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
		    }
		    */
		    
		    
		    $file_content = file($_FILES["file"]["tmp_name"]); 
		    $cont=0;
		    foreach($file_content as $sql_line)
		    { 
			if(trim($sql_line) != "" && strpos($sql_line, "--") == false)
			{         
			   //echo $sql_line.'<br/><br/>'; 
			    mysql_query($sql_line); 
			    $cont++;
			}
		    }
		     $app->enqueueMessage( JTEXT::_("IMPORT OK") .":".$cont." SQL"  )   ;
		}
		// Load the submenu.
		fieldsattachHelper::addSubmenu(JRequest::getCmd('view', 'fieldsattach'));
		parent::display(); 
        }
        
        
        /* creates a compressed zip file */
        function create_zip($files = array(),$destination = '',$overwrite = false) {
            
            //if the zip file already exists and overwrite is false, return false
            if(file_exists($destination) && !$overwrite) { return false; }
            //vars
            $valid_files = array();
            //if files were passed in...
            if(is_array($files)) {
                //cycle through each file
                foreach($files as $file) {
                //make sure the file exists
                   // echo "fiel".$file;
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
                }
            }
            //if we have good files...
            if(count($valid_files)) {
                //create the archive
                $zip = new ZipArchive();
                if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
                }
                //add the files
                foreach($valid_files as $file) {
                $zip->addFile($file,$file);
                }
                //debug
                //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

                //close the zip -- done!
                $zip->close(); 
                //check to make sure the file exists
                return file_exists($destination);
            }
            else
            { 
                return false;
            }
        }
        
     /* Reorder Galery type */
 	function fieldsattachimagesorderajax()
	{
		
		// Log the start
		// Initialise a basic logger with no options (once only).
		// Include the JLog class.
		jimport('joomla.log.log');
		
		JLog::addLogger(array());
		
		// Add a message.
		JLog::add('Logged 3', JLog::WARNING, 'fieldsattachimagesorderajax');
			    
		 
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
 
                $session =& JFactory::getSession();
		$fieldsattachid = JRequest::getVar("fieldsid");
		$order = JRequest::getVar("order");
		//Article -------------------
        $articleid =  $session->get('articleid');
		
		// Add a message.
		JLog::add($articleid, JLog::WARNING, 'Article ID');
		JLog::add($fieldsattachid, JLog::WARNING, 'Fieldsattahc ID');
		JLog::add($order, JLog::WARNING, 'Order');
                 
                if(empty($articleid) || empty($fieldsattachid) || empty($order)){
				//Empty  Nothing TODO
                }else{
				//SQL
				$tmporder = explode(",",$order);
				if(count($tmporder)>0)
				{
						$cont = 1;
						foreach($tmporder as $obj){
								//$query = 'UPDATE  #__fieldsattach_images SET ordering='.$obj.' WHERE id='.$fieldsattachid .' AND articleid='.$articleid;
								$query = 'UPDATE  #__fieldsattach_images SET ordering='.$cont.' WHERE id='.$obj ;
								
								JLog::add($query, JLog::WARNING, "sql");
								$db->setQuery($query);
								// Add a message.
								$db->execute();
								//$db->query();
								$cont++;
						}
				
				}
		}

	}
}
