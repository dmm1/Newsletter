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


class PNPluginLocationsArray extends PNPluginBaseArray
{
    function PNPluginLocationsArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
      {
        ModUtil::dbInfoLoad ('Locations');
        $pntable = DBUtil::getTables();
        $column  = $pntable['locations_location_column'];
       
        $sort    = "$column[locationid] DESC";
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_Locations_nItems', 1);
        return DBUtil::selectObjectArray ('locations_location', $where, $sort, 0, $nItems);
    }
}

