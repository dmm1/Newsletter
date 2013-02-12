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

class Newsletter_DBObject_Archive extends DBObject 
{
    function Newsletter_DBObject_Archive ($init=null, $key=null, $field=null)
    {
        $this->_objType          = 'newsletter_archives';
        $this->_objColumnPrefix  = 'nla';
        $this->_objPath          = 'archive';

        $this->_init ($init, $key, $field);
    }

    function delete ($data=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!$data) {
            $data = $this->_objData;
        }

        DBUtil::truncateTable($this->_objType);
        LogUtil::registerStatus(__('Archives flushed.', $dom));

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

    function deletebyid($id = 0)
    {
        if ($id > 0) {
            $where = "nla_id = ".$id;
            return DBUtil::deleteWhere($this->_objType, $where);
        }

        return false;
    }

    function getmaxid()
    {
        return (int)DBUtil::selectFieldMax('newsletter_archives', 'nla_id');
    }

    function getnextid()
    {
        $result = DBUtil::executeSQL("SHOW TABLE STATUS LIKE 'newsletter_archives'");
        if ($result) {
            $obj = DBUtil::marshallObjects($result);
            if ($obj) {
                return $obj[0]['Auto_increment'];
            }
        }
        return false;
    }

    function setnextid($id = 0)
    {
        if ($id > 0) {
            DBUtil::executeSQL("ALTER TABLE `newsletter_archives` AUTO_INCREMENT = ".$id);

            return $this->getnextid();
        }

        return false;
    }
}
