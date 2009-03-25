<?php
// ----------------------------------------------------------------------
//Zikula Application Framework
// Copyright (C) 2008 by the Zikula Development Team.
// http://www.zikula.org
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
 * Xanthia plugin
 * 
 * This file is a plugin for Xanthia, the Zikula implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.newsletter_maintenance.php,v 1.3 2004/08/10 15:29:13 markwest Exp $
 * @author       Devin Hayes
 * @link         http://www.invalidresponse.com
 * @copyright    Copyright (C) 2005 by D. Hayes
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to run maintenance tasks for the Newsletter module
 * 
 * Example
 * <!--[newsletter_maintenance]-->
 * 
 * @author       Devin Hayes
 * @since        29/10/05
 * @see          function.newsletter_maintenance.php::smarty_function_newsletter_maintenance()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       null     
 */

function smarty_function_newsletter_maintenance($params, &$smarty)
{

    unset($params);	
	
	if(!pnModAvailable('Newsletter')){
		return;
	}
	
	$today = date('w');
	
	// prune on monday before 7am	
	if($today==1 and date('G')<=7){
		pnModAPIFunc('Newsletter','user','prune_archives');
	}
	
	$send_day = pnModGetVar('Newsletter','send_day');
	if($send_day == $today){	
		pnModAPIFunc('Newsletter','user','send_newsletters');
	}
	
return;
}

?>