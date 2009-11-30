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


class PNPluginNewsArray extends PNPluginBaseArray
{
    function PNPluginNewsArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        pnModDBInfoLoad ('News');
        $pntable = pnDBGetTables();
        $column  = $pntable['stories_column'];
        $where   = "$column[published_status] = 0";
        $sort    = "$column[cr_date] DESC";
        $nItems  = pnModGetVar ('Newsletter', 'plugin_News_nItems', 1);
        return DBUtil::selectObjectArray ('stories', $where, $sort, 0, $nItems);
    }
}

