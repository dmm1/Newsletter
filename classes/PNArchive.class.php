<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
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
        LogUtil::setStatusMessage (__('Archives flushed.', $dom));

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
        $archive_expire = ModUtil::getVar('Newsletter','archive_expire', 0);
        if (!$archive_expire) {
            return true;
        }

        $expire_date = DateUtil::getDatetime_NextMonth ($archive_expire*-1, $format=DATEFORMAT_FIXED);
        $where = "DATEDIFF(nla_date, '$expire_date') > 0";
        return DBUtil::deleteWhere ($this->_objType, $where);
    }
}
