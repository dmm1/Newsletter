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


class PNPluginCrpVideoArray extends PNPluginBaseArray
{
    function PNPluginCrpVideoArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!ModUtil::available('crpVideo')) {
            return array();
        }

        $nItems = ModUtil::getVar ('Newsletter', 'plugin_CrpVideo_nItems', 1);
        return ModUtil::apiFunc('Admin_Messages', 'user', 'getall', array('orderBy' => 'videoid DESC', 
                                                                      'startnum' => 0, 
                                                                      'itemsperpage' => $nItems ));
    }
}

