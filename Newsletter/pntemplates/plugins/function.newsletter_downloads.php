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
// Newsletter module © 2006 InvalidResponse http://www.invalidresponse.com
// Plugin name: newsletter_downloads
// function.newsletter_downloads.php
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_postcalendar_events.php,v 1.0 2004/12/16
 * @author       Devin hayes
 * @link         http://www.invalidresponse.com 
 * @copyright    Copyright (C) 2006 by Devin Hayes
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to retrieve Recent Comments
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 *   - limit_text:    Limit the amount of characters to display
 * 
 * Example
 *   <!--[newsletter_downloads limit_items="2"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_downloads limit_items="2" category_id="5"]-->
		- OR - 
	    <!--[newsletter_downloads limit_items="2" category_id="5" sub_category_id="2"]-->

Displays 2 recent downloads from category_id 5 with 30 characters

	
	<div class="nl-content-wrapper">
		<h1>New Downloads</h1>
		<!--[newsletter_downloads limit_items="5" assign="new_downloads"]-->
		<ol>
		<!--[section name="new_downloads" loop="$new_downloads"]-->
			<li><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?name=Downloads&amp;req=viewdownloaddetails&amp;lid=<!--[$new_downloads[new_downloads].download_id]-->" title="<!--[$new_downloads[new_downloads].download_title|pnvarprephtmldisplay]-->"><!--[$new_downloads[new_downloads].download_title|pnvarprephtmldisplay]--> - <!--[$new_downloads[new_downloads].download_hits]--> hits</a></li>
		<!--[/section]-->
		</ol>
	</div>
			

*/
function smarty_function_newsletter_downloads($params, &$smarty)
{

	extract($params);
 	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	$column = &$pntable['downloads_downloads_column'];    

    $sql = "SELECT $column[lid], 
    			   $column[title], 
    			   $column[hits]
    		FROM $pntable[downloads_downloads]";
    if(isset($category_id)){
    	$where[] = "$column[cid]='".(int)pnVarPrepForStore($cid)."'";
    }
    if(isset($sub_category_id)){
    	$where[] = "$column[sid]='".(int)pnVarPrepForStore($sid)."'";
    }
    if(count($where)){
    	$sql .= "WHERE '".implode(' AND ',$where)."' ";
    }
	$sql .= "ORDER BY $column[date] DESC";
				
	if(isset($limit_items) AND $limit_items != null){
    	$sql .= " LIMIT ".(int)pnVarPrepForStore($limit_items)."";
    } else {
    	$sql .= " LIMIT 5";
	}
	
    $result = $dbconn->Execute($sql);
    
    if($dbconn->ErrorNo()<>0) {
		return $dbconn->ErrorMsg();
    }
		
	if($result->EOF){
    	return;
    }
    
    for (; !$result->EOF; $result->MoveNext()) {
    	list($lid, $title, $hits) = $result->fields;
    	$data[] = array('download_id'=>$lid,
     					'download_title'=>$title,
     					'download_hits'=>$hits);    
    }

	$result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>