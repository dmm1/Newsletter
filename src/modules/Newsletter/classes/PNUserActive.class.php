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


if (!Loader::loadClassFromModule('Newsletter', 'user')) {
    return LogUtil::registerError (__('Unable to load class [user] ... ', $dom));
}


class PNUserActive extends PNUser 
{
    function PNUserStatus ($init=null, $key=null, $field=null)
    {
        $this->PNUser ($init, $key, $field);
    }


    function save ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $id = FormUtil::getPassedValue ('id', null, 'GET');

        $data = $this->get ($id);
        $data['active'] = $data['active'] ? 0 : 1;

        $this->_objData = $data;
        $this->update ();
        return LogUtil::registerStatus (__("The user's approved status has been changed", $dom));
    }
}

