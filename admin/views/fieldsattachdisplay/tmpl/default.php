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
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<table class="adminform">
	<tr>
		<td width="100%" valign="top">
			<div id="cpanel">
         <h2>SHOW THE FIELDS</h2><br />
			   <h3>WITH CONTENT PLUGIN</h3>
			    <p>It Is automatic and more simple. The extra fields will  displayed after/before the content article.</p>
          <p style="color:#f00">NOTE: Only for a com_content article view.</p>
          <br />
	                    <h3>WITH API</h3>
                      
                            <h4>IMPORTANT!!!</h4>
                            Write this line in php view of component.<br /><br />

                            <code>// require helper file<br />
                            JLoader::register('fieldattach',  'components/com_fieldsattach/helpers/fieldattach.php');
                            </code>
                            <br /><br />
                            <h4>FUNCTIONS</h4>

                               
                              <h5>fieldattach::getFieldValue($articleid, $fieldid, $category  = false)</h5>
                              <table>
                                  <tr>
                                      <td valign="top">Parameters</td>
                                      <td>$id(Article id)<br />
                                      $fieldsids(field id)<br />
                                      $category, true or false (default false)</td>
                                  </tr>
                                  <tr>
                                      <td valign="top">Return</td>
                                      <td>HTML of field</td>
                                  </tr>
                              </table>
                              <hr />  
                               
                              <h5>fieldattach::getName($id, $fieldsids, $category  = false)</h5>
                              <table>
                                  <tr>
                                      <td valign="top">Parameters</td>
                                      <td>$id(Article id)<br />
                                      $fieldsids(field id)<br />
                                    $category, true or false (default false)</td></td>
                                  </tr>
                                  <tr>
                                      <td valign="top">Return</td>
                                      <td>Value of name of field</td>
                                  </tr>
                              </table>
                              <hr />
                              <h5>fieldattach::getValue($id, $fieldsids, $category  = false)</h5>
                              <table>
                                  <tr>
                                      <td valign="top">Parameters</td>
                                      <td>$id(Article id)<br />
                                      $fieldsids(field id)<br />
                                      $category, true or false (default false)</td>
                                  </tr>
                                  <tr>
                                      <td valign="top">Return</td>
                                      <td>Value of field</td>
                                  </tr>
                              </table>
                              <hr />
                              <h2>   Templating output fieldsattach</h2>
                              <p>
Now, from the package 2.8.6.2 and upper,  we have more control to the HTML output of extrafields.</p>

<p>
We can change the format of all extra type or of one individual extra field.</p>

<p>For example, if we need change the input type output:</p>

<ul>
  <li>
 Copy the source template: /plugins/fieldsattachment/input/tmpl/input.tpl.php</li>
<li>

 Paste to /templates/[YOUR TEMPLATE]/html/com_fieldsattach/fields/input.tpl.php

If you, only want change one id field, you can  change the name for (field id example = 1 ):
/templates/[YOUR TEMPLATE]/html/com_fieldsattach/fields/1_input.tpl.php
</li>
<li>
 Edit the file and change the format.</li>
</ul>

<p>

And this,  for all types (it have some exception like listunit)</p>
                              
			</div>
 
		</td>

		 
	</tr>
</table>
