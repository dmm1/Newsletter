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


class PNPluginMediashareArray extends PNPluginBaseArray
{
    function PNPluginMediashareArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('Mediashare')) {
            return array();
        }

        $nItems = pnModGetVar ('Newsletter', 'plugin_Mediashare_nItems', 1);
        return pnModAPIFunc('Mediashare', 'user', 'getList', array ('order'     => 'created', 
                                                                    'orderDir'  => 'DESC', 
                                                                    'recordPos' => 0, 
                                                                    'pageSize'  => $nItems));
    }
}

