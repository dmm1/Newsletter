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


class PNImportUsers extends PNObject 
{
    function PNImportUsers ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();
        $this->_objPath = 'import';
        $this->_init ($init, $key, $field);
    }


    function save ()
    {
        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0, 'GET'), 'GET');
        $masterKey = (string)pnModGetVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            return LogUtil::registerError ('Invalid admin_key received');
        }

        $zkUsers = pnModAPIFunc ('Users', 'user', 'getall');

        if (!Loader::loadArrayClassFromModule('Newsletter', 'user')) {
            return LogUtil::registerError ('Unable to load array class [user]');
        }

        $objArray = new PNUserArray ();
        $where    = 'nlu_uid > 0';
        $nlUsers  = $objArray->getWhere ('', '', -1, -1, 'email');

        $count                = 0;
        $joinDate             = DateUtil::getDatetime ();
        $importType           = pnModGetVar ('Newsletter', 'import_type', 1);
        $importFrequency      = pnModGetVar ('Newsletter', 'import_frequency', 1);
        $importActiveStatus   = pnModGetVar ('Newsletter', 'import_active_status', 1);
        $importApprovalStatus = pnModGetVar ('Newsletter', 'import_approval_status', 1);

        foreach ($zkUsers as $user) {
            if ($user['uid'] < 2 || !$user['activated']) {
                continue;
            }
            if (!isset($nlUsers[$user['email']])) {
                $newUser = array();
                $newUser['uid']       = $user['uid'];
                $newUser['name']      = $user['name'];
                $newUser['email']     = $user['email'];
                $newUser['lang']      = pnConfigGetVar('language');
                $newUser['type']      = $importType;
                $newUser['frequency'] = $importFrequency;
                $newUser['active']    = $importActiveStatus;
                $newUser['approved']  = $importApprovalStatus;
                DBUtil::insertObject ($newUser, 'newsletter_users');
                $count++;
            }
        }

        LogUtil::registerStatus (_NEWSLETTER_USERIMPORT_FINISH . " $count " . _NEWSLETTER_USERIMPORT_IMPORTED);
        return true;
    }
}

