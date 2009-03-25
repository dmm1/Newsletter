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


class PNPluginCrpCalendarArray extends PNPluginBaseArray
{
    function PNPluginCrpCalendarArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('crpCalendar')) {
            return array();
        }

        $nItems  = pnModGetVar ('Newsletter', 'plugin_CrpCalendar_nItems', 1);
        $modvars = array();
        $modvars['itemsperpage'] = $nItems;
        return pnModAPIFunc('Admin_Messages', 'user', 'getall', array('sortOrder' => 'videoid DESC', 
                                                                      'startnum' => 0, 
                                                                      'modvars' => $modvars));
    }
}

