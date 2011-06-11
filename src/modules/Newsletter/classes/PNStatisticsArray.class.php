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


class PNStatisticsArray extends PNObjectArray 
{
    function PNStatisticsArray($init=null, $where='')
    {
        $this->PNObjectArray();
    }


    function getWhere ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $data = array();
        $data['users']                     = DBUtil::selectObjectCount ('newsletter_users');
        $data['users_active']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_approved=1');
        $data['users_approved']            = DBUtil::selectObjectCount ('newsletter_users', 'nlu_active=1');
        $data['users_approved_and_active'] = DBUtil::selectObjectCount ('newsletter_users', 'nlu_approved=1 AND nlu_active=1');
        $data['users_registered']          = DBUtil::selectObjectCount ('newsletter_users', 'nlu_uid > 1');
        $data['users_text']                = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 1');
        $data['users_html']                = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 2');
        $data['users_textwithlinks']       = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 3');
        $data['users_weekly']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 1');
        $data['users_monthly']             = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 2');
        $data['users_yearly']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 3');
        $data['archives']                  = DBUtil::selectObjectCount ('newsletter_archives');

        $this->_objData = $data;
        return $this->_objData;
    }


    function getCount ($where='', $doJoin=false)
    {
        return count($this->_objData);
    }
}

