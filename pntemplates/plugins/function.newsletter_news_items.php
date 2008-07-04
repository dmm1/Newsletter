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
// Plugin name: newsletter_news_items
// function.newsletter_news_items.php © 2004 InvalidResponse http://www.invalidresponse.com
// ----------------------------------------------------------------------
/**
 * pnRender plugin
 * 
 * This file is a plugin for pnRender, the Zikula implementation of Smarty
 *
 * @package      Xanthia_Templating_Environment
 * @subpackage   pnRender
 * @version      $Id: function.xsitemap_newsitems.php,v 1.0 2004/12/16
 * @author       The PostNuke development team
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */ 

 
/**
 * Smarty function to retrieve recent news items
 * @author  Devin Hayes 2004
  * @author ported to zikula by Axel "guite" Guckelsberger 04.07.2008
 */

function smarty_function_newsletter_news_items($params, &$smarty) 
{
    extract($params);

    if(!pnModAvailable('News')){
        return;
    }

    pnModDBInfoLoad('News');

    $pntable = pnDBGetTables();
    $stable = $pntable['stories'];
    $scolumn = $pntable['stories_column'];

    $where = $scolumn['ihome'] . '=0';
    if(isset($topicid) && !empty($topicid)) {
        $where .= ' AND ' . $scolumn['topic'] . ' = \'' . (int) DataUtil::formatForStore($topicid) . '\'';
    }
    $orderBy = $scolumn['time'] . ' DESC';

    $numItems = 5;
    if(isset($limit_items) AND !empty($limit_items)) {
        $numItems = (int) $limit_items;
    }

    $items = DBUtil::selectObjectArray('stories');

    if (!count($items)) {
        return;
    }

    $data = array();

    foreach($items as $k => $item) {
        $hack = str_replace(array('</p>',"\r\n\r\n","\r\r","\n\n"), '::::', $item['hometext']);
        $story_parts = explode('::::', $hack);
        if(stristr($story_parts['0'], '<p>')){
            $story_parts['0'] .= '</p>';
        }

        $data[] = array('story_id' => $item['sid'],
                        'story_title' => $item['title'],
                        'story_summary' => $story_parts['0'],
                        'story_content' => $item['hometext']);
    }


    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $data);
    } else {
        return $data;
    }
}
?>