<?php
// $Id: function.add_additional_header.php,v 1.2 2004/08/16 11:23:29 r3ap3r Exp $
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
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the PostNuke implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.add_additional_title.php,v 1.2 2004/08/16 11:23:29 r3ap3r Exp $
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to add additional information to the <head> </head>
 * section of a PostNuke document
 * 
 * Available parameters:
 *   - title:   If set, the value is assigned to the global
 *               $additional_header array.  The value can be a single
 *               string or an array of strings.
 * 
 * Example:
 *   <!--[add_additional_title title='This is the title']-->
 *	OR
 *   <!--[add_additional_title header=$title]-->
 *
 *	In Non-pnRendered modules:
 *  $GLOBALS['add_additional_title'][] = 'This is the title';
 *  OR
 *	$GLOBALS['add_additional_title'] = array('This is the title','This is another title')
 * 
 * @author       Chris Miller
 * @since        14 August 2004
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the value of the last status message posted, or void if no status message exists
 */
function smarty_function_add_additional_title($args, &$smarty)
{
	if (!isset($args['title'])) {
		return;
	}
	
	global $additional_title;

	if (is_array($args['title'])) {
		foreach($args['title'] as $title) {
			$additional_title[] = $title;
		}
	} else {
		$additional_title[] = $args['title'];
	}
	return;
}

?>
