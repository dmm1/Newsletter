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
// Plugin name: newsletter_htmlpages
// function.newsletter_htmlpages.php
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_htmlpages.php,v 1.0 2004/12/16
 * @author       Devin hayes
 * @link         http://www.invalidresponse.com 
 * @copyright    Copyright (C) 2006 by Devin Hayes
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to dislay recent Web_Links's
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 * 
 * Example
 *   <!--[newsletter_htmlpages limit_items="2" limit_text="300"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_htmlpages limit_items="2" limit_text="300"]-->
		- OR - 
	   <!--[newsletter_htmlpages limit_items="2"]-->
		
Displays 2 recent htmlpages

	
	<div class="nl-content-wrapper">
	<h1>Recently Added Documents</h1>
	<!--[newsletter_htmlpages limit_items="2" limit_text="300" assign="pages"]-->
	<!--[section name="pages" loop="$pages"]-->
		<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?name=htmlpages&amp;func=display&amp;pid=<!--[$pages[pages].htmlpages_pageid]-->" title="<!--[$pages[pages].htmlpages_title|pnvarprepfordisplay]-->"><!--[$pages[pages].htmlpages_title]--></a></h3>
		<div><!--[$pages[pages].htmlpages_content|pnvarprephtmldisplay]--></div>
	<!--[/section]-->
	</div>						

*/
function smarty_function_newsletter_htmlpages($params, &$smarty)
{

	extract($params);
 	
	if (!pnModAvailable('htmlpages')) return;	
	if (!pnModAPILoad('htmlpages', 'user')) return;	
	if(!isset($limit_items) or $limit_items<1) $limit_items = 5;
	if(!isset($limit_text) or $limit_text<1) $limit_text = false;
	
    $dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('htmlpages');
	$table = $pntable['htmlpages'];
    $column = &$pntable['htmlpages_column'];
	$sql = "SELECT $column[pid],
				   $column[title],
                   $column[content]
            FROM $table
            ORDER BY $column[pid] DESC
            LIMIT $limit_items";
    $result = $dbconn->Execute($sql);
    
	$data = array();
    for (; !$result->EOF; $result->MoveNext()) {
    	list($pid, $title, $content) = $result->fields;
    	if($limit_text and strlen($content)>$limit_text){
    		$content = substr($content,0,$limit_text).'..';
    	}
    	$data[] = array('htmlpages_pageid'=>$pid,
     					'htmlpages_title'=>$title,
     					'htmlpages_content'=>$content);    
    }
    $result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>