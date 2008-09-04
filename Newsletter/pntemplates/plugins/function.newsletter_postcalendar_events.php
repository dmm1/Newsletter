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
// Author of file: D. Hayes (aka: InvalidResponse)
// Purpose of file: Newsletter with dynamic content
// Newsletter module © 2006 InvalidResponse http://www.invalidresponse.com
// Plugin name: newsletter_postcalendar_events
// function.newsletter_postcalendar_events.php
// ----------------------------------------------------------------------
// Original Author of file: foyleman
// Purpose of file:  News Module for pnTresMailer
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
 * 
 * Example
 *   <!--[newsletter_postcalendar_events limit_items="5"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_postcalendar_events limit_items="5"]-->

Displays 5 coming events

	
	<div class="nl-content-wrapper">
		<h1>Calendar Events</h1>
		<!--[newsletter_postcalendar_events limit_items="5" assign="events"]-->
		<!--[section name="events" loop="$events"]-->
			<h4><a><!--[$events[events].event_category|pnvarprephtmldisplay]--></a> <a>&gt;</a> <a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?module=PostCalendar&amp;func=view&amp;Date=<!--[$events[events].event_link_date]-->&amp;viewtype=details&amp;eid=<!--[$events[events].event_id]-->" title="<!--[$events[events].event_title|pnvarprephtmldisplay]-->"><!--[$events[events].event_title|pnvarprephtmldisplay]--></a></h4>
			<div><!--[$events[events].event_description|pnvarprephtmldisplay]--></div>
		<!--[/section]-->				
	</div>
			

*/
function smarty_function_newsletter_postcalendar_events($params, &$smarty)
{

	extract($params);
 	
 	if(!pnModAvailable('PostCalendar')) return;
 	
 	if(!isset($limit_items)){
 		$limit_items = '5';
 	}
 	
 	$data = array();
	$Month=date("m");
	$Day=date("d");
	$Year=date("Y");
	$starting_date = date('m/d/Y',mktime(0,0,0,$Month,$Day,$Year));
	$ending_date   = date('m/d/Y',mktime(0,0,0,$Month,$Day+7,$Year));
	$events = getevents($starting_date, $ending_date);
	if(!$events) {
		return;
	}
	
	$count = 0;
	foreach($events as $event){
		if($events['sharing'] == 0 OR $count==$limit_items) {
			continue;
		}

		$pc_hometext = stripslashes($event['eventdesc']);
		$pc_hometext = str_replace(":text:", "", $pc_hometext);
		$pc_eventDate = strtotime($event['eventdate']);
		$pc_endDate = strtotime($event['enddate']);
		$locale = pnConfigGetVar('locale');
		setlocale (LC_TIME, '$locale');
		$pc_eventDate = (ml_ftime(_DATEBRIEF, $pc_eventDate));
		$pc_endDate = (ml_ftime(_DATEBRIEF, $pc_endDate));

		$data[] = array('event_id'=>$event['eid'],
						'event_start_date'=>$pc_eventDate,
     					'event_end_date'=>($event['enddate']=='0000-00-00'?'(No end date)':$pc_endDate),
     					'event_title'=>stripslashes($event['eventtitle']),
     					'event_description'=>$pc_hometext,
     					'event_category'=>$event['eventcatname'],
     					'event_link_date'=>str_replace("-", "", $event['eventdate']));  		
		
	$count++;
	}
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

function getevents($starting_date, $ending_date)
{
	
	if (!pnModAPILoad('PostCalendar', 'user')) {
		echo "Failed to load PostCalenar API";
	}	
    
    $days = pnModAPIFunc('PostCalendar','user','pcGetEvents',array('start'=>$starting_date,'end'=>$ending_date));

	$returndata = array();
	foreach ($days as $events) {
		foreach($events as $event){
			$returndata[] = array('eventdate'=>$event['eventDate'],'eid'=>$event['eid'],'eventtitle'=>$event['title'],'eventdesc'=>$event['hometext'],'eventcatname'=>$event['catname'],'sharing'=>$event['sharing'], 'enddate'=>$event['endDate']);
		}
	}
	return $returndata;
}

?>