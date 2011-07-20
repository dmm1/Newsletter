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

class PNPluginNewsArray extends PNPluginBaseArray
{
    function PNPluginNewsArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        ModUtil::dbInfoLoad('News');
        $tables = DBUtil::getTables();
        $column = $tables['news_column'];
        $where  = "$column[published_status] = 0";
        $sort   = "$column[cr_date] DESC";
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_News_nItems', 1);

        return DBUtil::selectObjectArray ('news', $where, $sort, 0, $nItems);
    }
}
