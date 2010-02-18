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


class PNUserArray extends PNObjectArray 
{
    function PNUserArray($init=null, $where='')
    {
        $this->PNObjectArray();

        $this->_objType          = 'newsletter_users';
        $this->_objColumnPrefix  = 'nlu';
        $this->_objPath          = 'user';
        $this->_objSort          = 'cr_date DESC';

        if (!Loader::loadClassFromModule('Newsletter', 'user')) {
            LogUtil::registerError (__('Unable to load class [user] ... disabling auto-join for array class', $dom));
        } else {
            $obj = new PNUser ();
            $this->_objJoin = $obj->_objJoin;
        }
        
        $this->_init($init, $where);
    }


    function cleanFilter ($filter=array())
    {
        $filter['active']    =      (isset($filter['active'])    ? $filter['active']    : -1);
        $filter['approved']  =      (isset($filter['approved'])  ? $filter['approved']  : -1);
        $filter['lang']      =      (isset($filter['lang'])      ? $filter['lang']      : '');
        $filter['type']      = (int)(isset($filter['type'])      ? $filter['type']      : 0);
        $filter['frequency'] = (int)(isset($filter['frequency']) ? $filter['frequency'] : -1);
        $filter['search']    =      (isset($filter['search'])    ? $filter['search']    : 0);

        return $filter;
    }


    function genFilter ($filter=array())
    {
        $wheres = array();

        if ($filter['active'] > -1) {
            $wheres[] = "nlu_active = $filter[active]";
        }
        if ($filter['approved'] > -1) {
            $wheres[] = "nlu_approved= $filter[approved]";
        }
        if ($filter['frequency'] > -1) {
            $wheres[] = "nlu_frequency = $filter[frequency]";
        }
        if ($filter['lang']) {
            $wheres[] = "nlu_lang = '$filter[lang]'";
        }
        if ($filter['type']) {
            $wheres[] = "nlu_type = $filter[type]";
        }
        if ($filter['search']) {
            $wheres[] = "(nlu_name LIKE '%$filter[search]%' OR 
                          pn_uname LIKE '%$filter[search]%' OR 
                          nlu_email LIKE '%$filter[search]%')";
        }

        return implode (' AND ', $wheres);
    }


    function getSendable ($language='')
    {
        $allow_frequency_change = pnModGetVar ('Newsletter', 'allow_frequency_change', 0);
        $default_frequency = pnModGetVar ('Newsletter', 'default_frequency', 1);

        $where = "(nlu_active=1 AND nlu_approved=1)";
        if ($language) {
            $where = "(nlu_lang='$language' OR nlu_lang='')";
        }

        if (!$allow_frequency_change) {
            switch($default_frequency) {
                case 0: 
                    $checkDate = DateUtil::getDatetime_NextWeek (-1);
                    break;
                default:
                    $checkDate = DateUtil::getDatetime_NextMonth ($default_frequency*-1);
                    break;
            }
            $where .= " AND (nlu_last_send_date IS NULL OR (DATEDIFF(nlu_last_send_date, '$checkDate') <= 0))";
        }

        $users = $this->get ($where, 'id');

        if ($allow_frequency_change) {
            foreach ($users as $k=>$user) {
                switch($user['frequency']) {
                    case 0: 
                        $checkDate = DateUtil::getDatetime_NextWeek (-1);
                        break;
                    default:
                        $checkDate = DateUtil::getDatetime_NextMonth ($user['frequency']*-1);
                        break;
                }
                $diff = DateUtil::getDatetimeDiff_AsField ($user['last_send_date'], $checkDate);
                $users[$k]['send_now'] = (int)($diff > 0);
            }
        }

        $this->objData = $users;
        return $this->objData;
    }


    function selectPostProcess ($data=null) 
    {
        if (!$data) {
            $data = $this->_objData;
        }

        if (!Loader::loadClassFromModule('Newsletter', 'user')) {
            LogUtil::registerError (__('Unable to load class [user] ... disabling input post-processing for array class', $dom));
        } else {
            $obj = new PNUser ();
            foreach ($data as $k=>$v) {
                $obj->setData ($v);
                $data[$k] = $obj->selectPostProcess ($v);
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }
}

