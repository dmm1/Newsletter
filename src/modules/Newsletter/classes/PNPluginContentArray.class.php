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

class PNPluginContentArray extends PNPluginBaseArray
{
    function PNPluginContentArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        ModUtil::dbInfoLoad ('content');
        $pntable = DBUtil::getTables();
        $column  = $pntable['content_page_column'];
        $where   = "$column[active] = 1";
        $sort    = "$column[id] DESC";
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_Content_nItems', 1);
        return DBUtil::selectObjectArray ('content_page', $where, $sort, 0, $nItems);
    }
}
