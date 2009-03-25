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


class PNPluginContentArray extends PNPluginBaseArray
{
    function PNPluginContentArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        pnModDBInfoLoad ('content');
        $pntable = pnDBGetTables();
        $column  = $pntable['content_page_column'];
        $where   = "$column[active] = 1";
        $sort    = "$column[id] DESC";
	$nItems  = pnModGetVar ('Newsletter', 'plugin_Content_nItems', 1);
	return DBUtil::selectObjectArray ('content_page', $where, $sort, 0, $nItems);
    }
}

