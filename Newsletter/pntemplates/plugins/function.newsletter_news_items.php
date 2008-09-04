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
   *   <!--[newsletter_newsitems topicid="1" limit_items="2" limit_text="30"]-->
    *  <!--[newsletter_news_items topicid="1" limit_items="2"]-->
 */

function smarty_function_newsletter_news_items($params, &$smarty)
{
    if(!pnModAvailable('News')) return;
    pnModDBInfoLoad('News');
    $pntable = pnDBGetTables();
    $scolumn = $pntable['stories_column'];

   $where = '';
    if(isset($params['topicid']) && !empty($params['topicid'])) {
        $where = $scolumn['topic'] . ' = \'' . (int) DataUtil::formatForStore($params['topicid']) . '\'';
    }
    $orderBy = $scolumn['time'] . ' DESC';
    $numItems = (isset($params['limit_items']) && is_numeric($params['limit_items'])) ? $params['limit_items'] : 5;

    $items = DBUtil::selectObjectArray('stories', $where, $orderBy, -1, $numItems);
    if (!count($items)) return;

    $data = array();
    foreach($items as $k => $item) {
        $hack = str_replace(array('</p>',"\r\n\r\n","\r\r","\n\n"), '::::', $item['hometext']);
        $story_parts = explode('::::', $hack);
        if(stristr($story_parts['0'], '<p>')) {
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