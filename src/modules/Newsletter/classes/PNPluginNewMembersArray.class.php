<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */


class PNPluginNewMembersArray extends PNPluginBaseArray
{
    function PNPluginNewMembersArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        pnModDBInfoLoad ('Users');
        $pntable  = pnDBGetTables();
        $column   = $pntable['users_column'];
        $where    = "$column[uid] > 1";
        $sort     = "$column[user_regdate] DESC";
        $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
        $nItems   = pnModGetVar ('Newsletter', 'plugin_NewMembers_nItems', 1);
        return DBUtil::selectObjectArray ('users', $where, $sort, 0, $nItems);
    }
}

