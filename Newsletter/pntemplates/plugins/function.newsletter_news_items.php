<?php
// ----------------------------------------------------------------------
// Zikula Application Framework
// Copyright (C) 2008 by the Zikula Development Team.
// http://www.zikula.org
//----------------------------------------------------------------------
/**
 * Smarty function to retrieve recent news items
 * @author  Devin Hayes 2004
  * @author ported to zikula by Axel "guite" Guckelsberger 04.07.2008
   * @author modified by dmm for bugfixing  20.11.08
   
   *   <!--[newsletter_newsitems  published_status="0" limit_items="2" limit_text="30"]-->
    *  <!--[newsletter_news_items published_status="1" limit_items="2"]-->
 */

function smarty_function_newsletter_news_items($params, &$smarty)
{
    if(!pnModAvailable('News')) return;
    pnModDBInfoLoad('News');
    $pntable = pnDBGetTables();
    $scolumn = $pntable['stories_column'];


   

  $where = $scolumn['published_status'] . ' =0';
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
						'story_published_status' => $published_status,
						'story_content' => $item['hometext']);
    }

    if (isset($params['assign'])) {
        $smarty->assign($params['assign'], $data);
    } else {
        return $data;
    }
}