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


class PNPluginDizkusArray extends PNPluginBaseArray
{
    function PNPluginDizkusArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('Dizkus')) {
            return array();
        }

	pnModDBInfoLoad ('Dizkus');
        $nItems = pnModGetVar ('Newsletter', 'plugin_Dizkus_nItems', 1);
        return DBUtil::selectObjectArray ('dizkus_topics', '', 'topic_id DESC', 0, $nItems);
    }
}

