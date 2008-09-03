<?php
// ----------------------------------------------------------------------
// Zikula Application Framework
// Copyright (C) 2008 by the Zikula Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_new_members.php,v 1.1 2008/02/09
 * @author       Devin Hayes
 * @link         http://www.invalidresponse.com
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to retrieve Recent Members
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 * 
 * Example
 *   <!--[newsletter_new_members order="asc" limit_items="2"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_new_members limit_items="2"]-->
Displays 2 recent registers


	<div class="nl-content-wrapper">
		<h1>Welcome New Members!</h1>
		<!--[newsletter_new_members limit_items="5" assign="new_members"]-->
		<table class="nl-new-members">
			<tr>
				<th>Avatar</th>
				<th>User Name</th>
				<th>Register Date</th>
			</tr>
		<!--[section name="new_members" loop="$new_members"]-->
			<tr>
				<td><img src="<!--[$site_url|pnvarprepfordisplay]-->images/avatar/<!--[$new_members[new_members].user_avatar|pnvarprepfordisplay]-->" alt="avatar" /></td>
				<td><a href="<!--[$site_url|pnvarprepfordisplay]-->user.php?op=userinfo&amp;uname=<!--[$new_members[new_members].user_name|pnvarprephtmldisplay]-->" title="<!--[$new_members[new_members].user_name|pnvarprephtmldisplay]-->"><!--[$new_members[new_members].user_name|pnvarprephtmldisplay]--></a></td>
				<td><!--[$new_members[new_members].register_date]--></td>
			</tr>
		<!--[/section]-->
		</table>
	</div>


*/
function smarty_function_newsletter_new_members($params, &$smarty)
{

	extract($params);
 	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	$column = &$pntable['users_column'];    

    $sql = "SELECT $column[uid], 
    			   $column[uname], 
    			   $column[user_regdate]
    		FROM $pntable[users] WHERE $column[uid]>'1'
			ORDER BY $column[user_regdate] DESC";
			
					
	if(isset($limit_items) AND !empty($limit_items)){
    	$sql .= " LIMIT ".(int)pnVarPrepForStore($limit_items)."";
    } else {
    	$sql .= " LIMIT 5";
	}
	
    $result = $dbconn->Execute($sql);
    
    if($dbconn->ErrorNo()<>0) {
		die('DB Error: '.$dbconn->ErrorNo().': '.$dbconn->ErrorMsg().'');
    }
		
	if($result->EOF){
    	return;
    }
    
    for (; !$result->EOF; $result->MoveNext()) {
    	list($uid, $uname, $register_date) = $result->fields;    
    	$regdate = date('l F jS, Y', $register_date);    
     	$data[] = array('user_id'=>$uid,
     					'user_name'=>$uname,
     					'register_date'=>$register_date);    
	}
	
	$result->Close();

	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>