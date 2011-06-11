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


class PNPluginWeblinksArray extends PNPluginBaseArray
{
    function PNPluginWeblinksArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        pnModDBInfoLoad ('Web_Links');
        $pntable  = pnDBGetTables();
        $column   = $pntable['links_links_column'];
        $sort     = "$column[lid] DESC";
        $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
        $nItems   = pnModGetVar ('Newsletter', 'plugin_Weblinks_nItems', 1);
        return DBUtil::selectObjectArray ('links_links', $where, $sort, 0, $nItems);
    }
}
