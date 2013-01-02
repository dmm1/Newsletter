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

class Newsletter_DBObject_ImportUsers extends DBObject 
{
    function Newsletter_DBObject_ImportUsers ($init=null, $key=null, $field=null)
    {
        $this->_objPath = 'import';
        $this->_init ($init, $key, $field);
    }

    function save()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0, 'GET'), 'GET');
        $masterKey = (string)ModUtil::getVar('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            return LogUtil::registerError(__('Invalid admin_key received', $dom));
        }

        $zkUsers = ModUtil::apiFunc('Users', 'user', 'getall');

        if (!is_class('Newsletter_DBObject_User')) {
            return LogUtil::registerError(__('Unable to load array class [user]', $dom));
        }

        $objArray = new Newsletter_DBObject_UserArray ();
        $where    = 'nlu_uid > 0';
        $nlUsers  = $objArray->getWhere('', '', -1, -1, 'email');

        $count                = 0;
        $joinDate             = DateUtil::getDatetime();
        $importType           = ModUtil::getVar('Newsletter', 'import_type', 1);
        $importFrequency      = ModUtil::getVar('Newsletter', 'import_frequency', 1);
        $importActiveStatus   = ModUtil::getVar('Newsletter', 'import_active_status', 1);
        $importApprovalStatus = ModUtil::getVar('Newsletter', 'import_approval_status', 1);

        foreach ($zkUsers as $user) {
            if ($user['uid'] < 2 || !$user['activated']) {
                continue;
            }
            if (!isset($nlUsers[$user['email']])) {
                $newUser = array();
                $newUser['uid']       = $user['uid'];
                $newUser['name']      = $user['name'];
                $newUser['email']     = $user['email'];
                $newUser['lang']      = System::getVar('language');
                $newUser['type']      = $importType;
                $newUser['frequency'] = $importFrequency;
                $newUser['active']    = $importActiveStatus;
                $newUser['approved']  = $importApprovalStatus;
                DBUtil::insertObject($newUser, 'newsletter_users');
                $count++;
            }
        }

        LogUtil::registerStatus(__('Import finished %s user was imported.', 'Import finished %s users were imported.', $count, $dom));
        return true;
    }
}
