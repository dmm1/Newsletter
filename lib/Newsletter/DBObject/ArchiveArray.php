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

class Newsletter_DBObject_ArchiveArray extends DBObjectArray 
{
    function Newsletter_DBObject_ArchiveArray($init=null, $where='')
    {
        $this->_objType          = 'newsletter_archives';
        $this->_objColumnPrefix  = 'nla';
        $this->_objPath          = 'newsletter_archives';
        $this->_objSort          = 'date DESC';

        $this->_init($init, $where);
    }

    function get ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        if (empty($sort)) {
            $sort  = 'id DESC';
        }
        $archives = $this->getWhere ($where, $sort, $limitOffset, $limitNumRows, $assocKey, $force, $distinct);
        foreach ($archives as $k=>$archive) {
            $archives[$k]['date_display'] = DateUtil::getDatetime_Date ($archive['date']);
        }

        $this->_objData = $archives;
        return $this->_objData;
    }
}
