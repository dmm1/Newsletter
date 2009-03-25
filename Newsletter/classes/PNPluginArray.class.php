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


class PNPluginArray extends PNObjectArray 
{
    function PNPluginArray($init=null, $where='')
    {
        $this->PNObjectArray();
    }


    function getWhere ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $this->_objData = NewsletterUtil::getPluginClasses ();
        return $this->_objData;
    }


    function getCount ($where='', $doJoin=false)
    {
        return count($this->_objData);
    }
}

