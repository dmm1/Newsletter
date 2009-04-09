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


class PNArchive extends PNObject 
{
    function PNArchive ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();

        $this->_objType          = 'newsletter_archives';
        $this->_objColumnPrefix  = 'nla';
        $this->_objPath          = 'archive';

        $this->_init ($init, $key, $field);
    }


    function delete ($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        DBUtil::truncateTable ($this->_objType);
        LogUtil::setStatusMessage ('Archives flushed.');

        $this->_objData = $data;
        return $this->_objData;
    }


    function getRecent ()
    {
        $checkDate = DateUtil::getDatetime_NextWeek (-1);
        $where     = "DATEDIFF(nla_date, '$checkDate') > 0";
        return $this->getWhere ($where);
    }


    function prune ($data=null)
    {
        $archive_expire = pnModGetVar('Newsletter','archive_expire', 0);
        if (!$archive_expire) {
            return true;
        }

        $expire_date = DateUtil::getDatetime_NextMonth ($archive_expire*-1, $format=DATEFORMAT_FIXED);
        $where = "DATEDIFF(nla_date, '$expire_date') > 0";
        return DBUtil::deleteWhere ($this->_objType, $where);
    }
}

