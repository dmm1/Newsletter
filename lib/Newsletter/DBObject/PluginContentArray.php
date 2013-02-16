<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class Newsletter_DBObject_PluginContentArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginContentArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!ModUtil::available('content')) {
            return array();
        }

        ModUtil::dbInfoLoad ('content');
        $table = DBUtil::getTables();
        $column  = $table['content_page_column'];
        $where   = "$column[active] = 1";
        $sort    = "$column[id] DESC";
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_Content_nItems', 1);

        $items = DBUtil::selectObjectArray ('content_page', $where, $sort, 0, $nItems);

        // filter by date is given, remove older data
        if ($filtAfterDate) {
            foreach (array_keys($items) as $k) {
                if ($items[$k]['cr_date'] < $filtAfterDate) {
                    unset($items[$k]);
                }
            }
        }

        return $items;
    }
}
