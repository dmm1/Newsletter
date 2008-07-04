<?php
// $Id: function.title.php,v 1.3 2004/08/10 15:29:13 markwest Exp $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
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
/**
 * Xanthia plugin
 * 
 * This file is a plugin for Xanthia, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   Xanthia
 * @version      $Id: function.title.php,v 1.3 2004/08/10 15:29:13 markwest Exp $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to generate the title for the page
 * 
 * Example
 * <!--[title]-->
 * 
 * @author       Mark West
 * @since        29/03/04
 * @see          function.title.php::smarty_function_title()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the title
 */
function smarty_function_title($params, &$smarty) 
{
    extract($params); 
	unset($params);
	
	define('_DELIMITER','-');
	$max_len = 64;
	$slogan = pnConfigGetVar('slogan');
	$sitename = pnConfigGetVar('sitename');
	
	$title =  $sitename.' '._DELIMITER.' '.$slogan;
	
	if (isset($GLOBALS['info']) && is_array($GLOBALS['info'])) {
		$title = strip_tags($GLOBALS['info']['title']).' '._DELIMITER.' '.$slogan;
	} 
	
	global $additional_title;
	if(is_array($additional_title) and !empty($additional_title['0'])){
		$add_title = @implode(' '._DELIMITER.' ', $additional_title);
		$add_title_count = strlen($add_title);
		if($add_title_count < ($max_len - strlen($slogan))){
			$title = $add_title.' '._DELIMITER.' '.$slogan;
		} else if($add_title_count > $max_len){
			$junk = array_shift($additional_title);
			$add_title = @implode(' '._DELIMITER.' ', $additional_title);
			$title = $add_title;
		} else {
			$title = $add_title;
		}
		
	}

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $title);
    } else {
        return $title;
    }

}

?>