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
        $otTest  = (int)FormUtil::getPassedValue ('otTest', FormUtil::getPassedValue('otTest', 0, 'GET'), 'GET');
        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0, 'GET'), 'GET');
        $masterKey = (string)ModUtil::getVar('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            return LogUtil::registerError(__('Invalid admin_key received'));
        }

        $importType           = ModUtil::getVar('Newsletter', 'import_type', 1);
        $importFrequency      = ModUtil::getVar('Newsletter', 'import_frequency', 1);
        $importActiveStatus   = ModUtil::getVar('Newsletter', 'import_active_status', 1);
        $importApprovalStatus = ModUtil::getVar('Newsletter', 'import_approval_status', 1);
        $importActiveLastDays = (int)ModUtil::getVar('Newsletter', 'import_activelastdays', 0);

        $where = "WHERE activated AND lastlogin<>'' AND lastlogin<>'0000-00-00 00:00:00' AND lastlogin<>'1970-01-01 00:00:00'";
        if ($importActiveLastDays > 0) {
            // get time for comparison
            $aftertime = date('Y-m-d H:i:s', time() - ($importActiveLastDays * 24 * 60 * 60));
            $where .= " AND lastlogin>'".$aftertime."'";
        }
        $orderBy = 'ORDER BY uid';

        $zkUsers = DBUtil::selectObjectArray('users', $where, $orderBy);

        if (!class_exists('Newsletter_DBObject_User')) {
            return LogUtil::registerError(__('Unable to load array class [user]'));
        }

        $objArray = new Newsletter_DBObject_UserArray ();
        $where    = 'nlu_uid > 0';
        $nlUsers  = $objArray->getWhere('', '', -1, -1, 'email');

        $count                = 0;
        $countskiped          = 0;
        $joinDate             = DateUtil::getDatetime();

        foreach ($zkUsers as $user) {
            if ($user['uid'] < 2 || !$user['activated']) {
                continue;
            }
            if (isset($nlUsers[$user['email']])) {
                $countskiped++;
            } else {
                $newUser = array();
                $newUser['uid']       = $user['uid'];
                $newUser['name']      = $user['name'];
                $newUser['email']     = $user['email'];
                $newUser['lang']      = System::getVar('language_i18n', 'en');
                $newUser['type']      = $importType;
                $newUser['frequency'] = $importFrequency;
                $newUser['active']    = $importActiveStatus;
                $newUser['approved']  = $importApprovalStatus;
                if (!$otTest) {
                    DBUtil::insertObject($newUser, 'newsletter_users');
                }
                $count++;
            }
        }

        if ($otTest) {
            LogUtil::registerStatus(__f('Users to import: ' . $count));
        } else {
            LogUtil::registerStatus(_fn('Import finished. %s user was imported.', 'Import finished. %s users were imported.', $count, $count));
        }
        if ($countskiped > 0) {
            LogUtil::registerStatus(__f('Users skipped (dublicate email): ' . $countskiped));
        }
        return;
    }
}
