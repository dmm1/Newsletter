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

class PNPluginNewMembersArray extends PNPluginBaseArray
{
    function PNPluginNewMembersArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        ModUtil::dbInfoLoad('Users');
        $tables   = DBUtil::getTables();
        $column   = $tables['users_column'];
        $where    = "$column[uid] > 1";
        $sort     = "$column[user_regdate] DESC";
        $enableML = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        $nItems   = ModUtil::getVar ('Newsletter', 'plugin_NewMembers_nItems', 1);

        return DBUtil::selectObjectArray ('users', $where, $sort, 0, $nItems);
    }
}
