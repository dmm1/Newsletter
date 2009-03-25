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
    return LogUtil::registerError ('Unable to load class [user] ... ');
}


class PNUserApproved extends PNUser 
{
    function PNUserApproval ($init=null, $key=null, $field=null)
    {
        $this->PNUser ($init, $key, $field);
    }


    function save ()
    {
        $id = FormUtil::getPassedValue ('id', null, 'GET');

	$data = $this->get ($id);
	$data['approved'] = $data['approved'] ? 0 : 1;

        $this->_objData = $data;
        $this->update ();
        return LogUtil::registerStatus (_NEWSLETTER_CHANGE_ACTIVE_STATUS_CHANGED);
    }
}

