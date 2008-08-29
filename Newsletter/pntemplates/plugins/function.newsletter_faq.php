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
// Plugin name: newsletter_faq
// function.newsletter_faq.php
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.newsletter_faq.php,v 1.0 2004/12/16
 * @author       Devin hayes
 * @link         http://www.invalidresponse.com 
 * @copyright    Copyright (C) 2006 by Devin Hayes
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to dislay recent FAQ's
 * 
 * 
 * Available parameters:
 *   - limit_items:   Limit the amount of items displayed
 * 
 * Example
 *   <!--[newsletter_faq limit_items="2" category_id="5"]-->
 * 
 * 
 * @author       Devin Hayes
 * @since        12/16/2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      value
 */

/*

Usage: <!--[newsletter_faq limit_items="2"]-->
		- OR - 
	   <!--[newsletter_faq limit_items="2" category_id="5"]-->
		
Displays 2 recent FAQs from category_id 5

	
	<div class="nl-content-wrapper">
		<h1>FAQ's</h1>
		<!--[newsletter_faq limit_items="5" assign="faq"]-->
			<!--[section name="faq" loop="$faq"]-->
				<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?name=FAQ&id_cat=<!--[$faq[faq].faq_category_id]-->#q<!--[$faq[faq].faq_id]-->" title="FAQ"><!--[$faq[faq].faq_question]--></a></h3>
				<div><!--[$faq[faq].faq_answer]--></div>
			<!--[/section]-->
	</div>
			

*/
function smarty_function_newsletter_faq($params, &$smarty)
{

	extract($params);
 	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	$column = &$pntable['faqanswer_column'];

    $sql = "SELECT $column[id], 
    			   $column[id_cat], 
    			   $column[question],
    			   $column[answer],
    		FROM $pntable[faqanswer]";
    if(isset($category_id)){
    	$sql .= " WHERE $column[id_cat]='".(int)pnVarPrepForStore($category_id)."' ";
    }

	$sql .= "ORDER BY $column[id] DESC";
				
	if(isset($limit_items) AND $limit_items != ''){
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
    	list($id, $id_cat, $question, $answer) = $result->fields;
    	$data[] = array('faq_id'=>$id,
     					'faq_category_id'=>$id_cat,
     					'faq_question'=>$question,
     					'faq_answer'=>$answer);    
    }

	$result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>