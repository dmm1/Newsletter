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


class PNPluginEventLinerArray extends PNPluginBaseArray
{
    function PNPluginEventLinerArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        ModUtil::dbInfoLoad ('EventLiner');
        $pntable = DBUtil::getTables();
        $column  = $pntable['eventliner_events_column'];
        $where   = '';
        $sort    = "$column[id] DESC";
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_EventLiner_nItems', 1);
        return DBUtil::selectObjectArray ('EventLiner_events', $where, $sort, 0, $nItems);
    }
}

