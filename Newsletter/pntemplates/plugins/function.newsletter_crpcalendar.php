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
// Module name: Newsletter
// Original Author of file: D. Hayes (aka: InvalidResponse)
// Purpose of file: Newsletter with dynamic content
// Newsletter module © 2006 InvalidResponse http://www.invalidresponse.com
// Plugin name: newsletter_pages
// function.newsletter_pages.php
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_pages.php,v 1.0 2008/04/09
 * @author       Dominik Mayer
 * @copyright    Copyright (C) 2008 by dmm
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to dislay recent Page`s
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 * 
 * Example
 *   <!--[newsletter_pages limit_items="2" limit_text="300"]-->
 * 

Usage: <!--[newsletter_pages limit_items="2" limit_text="300"]-->
		- OR - 
	   <!--[newsletter_pages limit_items="2"]-->
		
Displays 2 recent pages

	
	<div class="nl-content-wrapper">
	<h1>Recently Added Documents</h1>
	<!--[newsletter_pages limit_items="2" limit_text="300" assign="pages"]-->
	<!--[section name="pages" loop="$pages"]-->
		<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?name=pages&amp;func=display&amp;pid=<!--[$pages[pages].pages_pageid]-->" title="<!--[$pages[pages].pages_title|pnvarprepfordisplay]-->"><!--[$pages[pages].pages_title]--></a></h3>
		<div><!--[$pages[pages].pages_content|pnvarprephtmldisplay]--></div>
	<!--[/section]-->
	</div>						

*/
function smarty_function_newsletter_crpcalendar($params, &$smarty)
{

	extract($params);
 	
	if (!pnModAvailable('crpcalendar')) return;	
	if (!pnModAPILoad('crpcalendar', 'user')) return;	
	if(!isset($limit_items) or $limit_items<1) $limit_items = 5;
	if(!isset($limit_text) or $limit_text<1) $limit_text = false;
	
    $dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('crpcalendar');
	$table = $pntable['crpcalendar'];
    $column = &$pntable['crpcalendar_column'];
	$sql = "SELECT $column[eventid],
				   $column[title],
                   $column[event_text],
				   $column[location],
				   $column[start_date]
            FROM $table
            ORDER BY $column[title] DESC
            LIMIT $limit_items";
    $result = $dbconn->Execute($sql);
    
	$data = array();
    for (; !$result->EOF; $result->MoveNext()) {
    	list($eventid, $title, $start_date, $location, $event_text) = $result->fields;
    	if($limit_text and strlen($content)>$limit_text){
    		$content = substr($content,0,$limit_text).'..';
    	}
    	$data[] = array('crpcalendar_eventid'=>$eventid,
						'crpcalendar_title'=>$title,
						'crpcalendar_start_date'=>$start_date,
						'crpcalendar_location'=>$location,
     					'crpcalendar_event_text'=>$event_text);    
    }
    $result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>