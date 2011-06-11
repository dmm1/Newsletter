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


class PNPluginTimeItArray extends PNPluginBaseArray
{
    function PNPluginTimeItArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        pnModDBInfoLoad ('TimeIt');
        $pntable = pnDBGetTables();
        $column  = $pntable['TimeIt_events_column'];
        $where   = "$column[status] = 1 AND ($column[sharing] = 2 OR $column[sharing] = 3 OR $column[sharing] = 4)"; // FIXME!! is this correct???
        $sort    = "$column[id] DESC";
        $nItems  = pnModGetVar ('Newsletter', 'plugin_TimeIt_nItems', 1);
        return DBUtil::selectObjectArray ('TimeIt_events', $where, $sort, 0, $nItems);
    }
}

