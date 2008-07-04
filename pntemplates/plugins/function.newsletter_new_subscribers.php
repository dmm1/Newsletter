<?php
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2004 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
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
// Module name: Newsletter
// Original Author of file: D. Hayes (aka: InvalidResponse)
// Purpose of file: Newsletter with dynamic content
// xSiteMap module © 2004 InvalidResponse http://www.invalidresponse.com
// Plugin name: newsletter_new_subscribers
// function.newsletter_new_subscribers.php
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_new_subscribers.php,v 1.0 2004/12/16
 * @author       Devin Hayes
 * @link         http://www.invalidresponse.com
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to retrieve Recent Subscribers
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 * 
 * Example
 *   <!--[newsletter_new_subscribers limit_items="2"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_new_subscribers limit_items="5"]-->
Displays 2 recent subscribers


	<div class="nl-content-wrapper">
		<h1>Welcome New Subscribers!</h1>
		<!--[newsletter_new_subscribers limit_items="5" assign="new_subscribers"]-->
		<table class="nl-new-members">
			<tr>
				<th>User Name</th>
				<th>Register Date</th>
			</tr>
		<!--[section name="new_subscribers" loop="$new_subscribers"]-->
			<tr>
				<td><!--[$new_subscribers[new_subscribers].user_name|pnvarprephtmldisplay]--></td>
				<td><!--[$new_subscribers[new_subscribers].join_date]--></td>
			</tr>
		<!--[/section]-->
		</table>
	</div>


*/

function smarty_function_newsletter_new_subscribers($params, &$smarty)
{

	extract($params);
 	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('Newsletter');
	$column = &$pntable['newsletter_users_column'];    

    $sql = "SELECT $column[user_name], 
    			   $column[join_date]
    		FROM $pntable[newsletter_users] WHERE $column[approved]='1'
			ORDER BY $column[join_date] DESC";
			
					
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
    	list($user_name, $join_date) = $result->fields;    
    	$join_date = date('l F jS, Y', $join_date);    
     	$data[] = array('user_name'=>$user_name,
     					'join_date'=>$join_date);    
	}
	
	$result->Close();

	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>