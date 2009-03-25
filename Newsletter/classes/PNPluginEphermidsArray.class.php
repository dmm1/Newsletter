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


class PNPluginEphermidsArray extends PNPluginBaseArray
{
    function PNPluginEphermidsArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('Ephermids')) {
            return array();
        }

        $nItems = pnModGetVar ('Newsletter', 'plugin_Ephermids_nItems', 1);
        return pnModAPIFunc('Ephermids', 'user', 'getall', array('order' => 'mid DESC', 
                                                                 'startnum' => 0, 
                                                                 'numitems' => $nItems));
    }
}

