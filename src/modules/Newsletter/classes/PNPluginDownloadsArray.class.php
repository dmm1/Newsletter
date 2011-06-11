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


class PNPluginDownloadsArray extends PNPluginBaseArray
{
    function PNPluginDownloadsArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!ModUtil::available('Downloads')) {
            return array();
        }

        $nItems = ModUtil::getVar ('Newsletter', 'plugin_Downloads_nItems', 1);
        return ModUtil::apiFunc('Downloads', 'user', 'get_download_info', array ('sortby'      => 'date', 
                                                                             'cclause'     => 'DESC',
                                                                             'sort_active' => true,
                                                                             'cid'         => 0,
                                                                             'get_by_cid'  => 0,
                                                                             'sort_date'   => 0,
                                                                             'get_by_date' => 0,
                                                                             'start'       => 0, 
                                                                             'limit'       => $nItems));
    }
}

