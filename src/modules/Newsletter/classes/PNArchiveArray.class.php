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


class PNArchiveArray extends PNObjectArray 
{
    function PNArchiveArray($init=null, $where='')
    {
        $this->PNObjectArray();

        $this->_objType          = 'newsletter_archives';
        $this->_objColumnPrefix  = 'nla';
        $this->_objPath          = 'newsletter_archives';
        $this->_objSort          = 'date DESC';

        $this->_init($init, $where);
    }


    function get ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $sort  = 'id DESC';
        $archives = $this->getWhere ($where, $sort, $limitOffset, $limitNumRows, $assocKey, $force, $distinct);
        foreach ($archives as $k=>$archive) {
            $archives[$k]['date_display'] = DateUtil::getDatetime_Date ($archive['date']);
        }

        $this->_objData = $archives;
        return $this->_objData;
    }
}

