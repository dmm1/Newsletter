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

class Newsletter_DBObject_User extends DBObject 
{
    public function __construct($init=null, $key=null, $field=null)
    {
        $this->_objType         = 'newsletter_users';
        $this->_objColumnPrefix = 'nlu';
        $this->_objPath         = 'user';

        $this->_objJoin   = array();
        $this->_objJoin[] = array(
                                'join_table'          => 'users',
                                'join_field'          => array('uname', 'email'),
                                'object_field_name'   => array('user_name', 'user_email'), 
                                'compare_field_table' => 'uid',
                                'compare_field_join'  => 'uid'
                            );

        $this->_init($init, $key, $field);
    }

    public function delete()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $requestURI = $_SERVER['REQUEST_URI'];

        if (strpos($requestURI, 'admin') !== false) {
            $id = (int)FormUtil::getPassedValue('id', 0, 'GET');
            if (!$id) {
                return LogUtil::registerArgsError();
            }
            $where = "nlu_id=$id";
            $ret = DBUtil::deleteWhere($this->_objType, $where);
        } else {
            $key = $this->getSelectionKey();
            if (!$key) {
                return false;
            }

            $userObj = new Newsletter_DBObject_User();
            $data    = $userObj->getUser($key, null, null);
            if (!$data) {
                LogUtil::registerError(__('No such item found.', $dom));
                return false;
            }

            $where = $this->genWhere($key, null, null);
            if (!$where) {
                return LogUtil::registerArgsError();
            }

            $ret = DBUtil::deleteWhere($this->_objType, $where);
            if ($ret) {
                LogUtil::registerStatus(__('You have unsubscribed from our newsletter', $dom));

                $view = Zikula_View::getInstance('Newsletter', false);
                $view->assign('user_name',  $data['uid'] ? UserUtil::getVar('uname', $data['uid']) : $data['name']);
                $view->assign('user_email', $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email']);
                $view->assign('site_url',   System::getBaseUrl());
                $message = $view->fetch('email/user_unsubscribe.tpl');
                $send_from_address = ModUtil::getVar('Newsletter', 'send_from_address');
                ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email'],
                                                                        'fromaddress'=> $send_from_address,
                                                                        'subject'    => __('Newsletter Subscription Cancelled', $dom),
                                                                        'body'       => $message,
                                                                        'html'       => true));
            }
        }

        return true;
    }

    public function genWhere($key, $approved=null, $active=null)
    {
        $wheres = array();
        if (is_numeric($key)) { // numeric arg == uid; string arg == email
            $wheres[] = "nlu_uid=$key";
        } else {
            $wheres[] = 'nlu_email=\'' . DataUtil::formatForStore($key) . '\'';
        }

        if ($approved !== null) { 
            $approved = (int)$approved;
            $wheres[] = "nlu_approved=$approved"; 
        }
        if ($active !== null) { 
            $active = (int)$active;
            $wheres[] = "nlu_active=$active"; 
        }

        return implode(' AND ', $wheres);
    }

    public function getDataFromInputPostProcess($data=null)
    {
        if (!$data) { 
            $data = $this->_objData;
        }

        if (!isset($data['active'])) {
            $data['active'] = 0;
        }

        if (!isset($data['approved'])) {
            $data['approved'] = 0;
        }

        if (!isset($data['uid']) && UserUtil::isLoggedIn()) {
            $data['uid'] = UserUtil::getVar ('uid');
        }

        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        if (!$enableML && !$data['lang']) {
            $data['lang'] = System::getVar('language_i18n', 'en');
        }

        $limitType = ModUtil::getVar('Newsletter', 'limit_type', 0);
        if ($limitType) {
            $data['type'] = $limitType;
        }

        $this->_objData = $data;
        return $this->_objData;
    }

    public function getSelectionKey()
    {
        $key = false;
        if (UserUtil::isLoggedIn()) {
            $key = UserUtil::getVar('uid');
        } else {
            $data = $this->_objData;
            if (!isset($data['email']) || !$data['email']) {
                return LogUtil::registerArgsError();
            }
            $key = $data['email'];
        }

        return $key;
    }

    public function getUser($key, $approved=null, $active=null)
    {
        $where = $this->genWhere($key, $approved, $active);
        if (!$where) {
            return LogUtil::registerArgsError();
        }

        return $this->getWhere($where);
    }


    public function insertPreProcess($data=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!$data) {
            $data = $this->_objData;
        }

        $auto_approve_registrations = ModUtil::getVar('Newsletter', 'auto_approve_registrations', 0);
        if ($auto_approve_registrations) {
            $data['approved'] = 1;
        }
        $data['active'] = 1;

        if (UserUtil::isLoggedIn()) {
            if ($data['uid']) {
                $data['name']  = UserUtil::getVar ('uname', $data['uid']);
                $data['email'] = ($data['email'] == '') ? UserUtil::getVar ('email', $data['uid']) : $data['email'];
            } else {
                LogUtil::registerError(__('Logic Error: logged-in user does not have his subscription UID set!', $dom));
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }

    public function insert()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $requestURI = $_SERVER['REQUEST_URI'];

        if (strpos($requestURI, 'admin') !== false) {
            $data     = $this->_objData;
            $where    = 'nlu_email=\'' . DataUtil::formatForStore($data['email']) . '\'';
            $userObj  = new Newsletter_DBObject_User ();
            $userData = $userObj->getWhere($where);
            if ($userData) {
                LogUtil::registerError(__('This email address is already subscribed to our newsletter!', $dom));
                return false;
            }
            return (bool) DBUtil::insertObject($data, $this->_objType);
        } else {
            $key = $this->getSelectionKey ();
            if (!$key) {
                return false;
            }
            $userObj  = new Newsletter_DBObject_User();
            $userData = $userObj->getUser($key, null, null);
            if ($userData) {
                LogUtil::registerError(__('You are already subscribed to our newsletter!', $dom));
                return false;
            }
        }

        $data = parent::insert();
        if ($data) {
            LogUtil::registerStatus(__('You have been subscribed to our newsletter', $dom));

            $view = Zikula_View::getInstance('Newsletter', false);
            $view->assign ('user_name', $data['uid'] ? UserUtil::getVar('uname', $data['uid']) : $data['name']);
            $view->assign ('user_email', $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email']);
            $view->assign ('site_url', ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'unsubscribe'), null, '', true));
            $user_message = $view->fetch('email/user_notify.tpl');
            $send_from_address = ModUtil::getVar ('Newsletter', 'send_from_address');
            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email'],
                                                                'fromaddress'=> $send_from_address,
                                                                'subject'    => __('Newsletter Subscription Received', $dom),
                                                                'body'       => $user_message,
                                                                'html'       => true));

            if (ModUtil::getVar('Newsletter', 'notify_admin', 0)) {
                $admin_message = $view->fetch ('email/admin_notify.tpl');
                ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $send_from_address,
                                                                    'fromaddress'=> $send_from_address,
                                                                    'subject'    => __('Newsletter Subscription', $dom),
                                                                    'body'       => $admin_message,
                                                                    'html'       => true));
            }
        }

        return true;
    }

    public function selectPostProcess($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        // if a uid is set, this is a registered user so we can 
        // take the info fields auto-joined from the users table
        if (isset($data['uid']) && $data['uid']) {
            $data['name']  = $data['user_name'];
            $data['email'] = ($data['email'] == '') ? $data['user_email'] : $data['email'];
        }

        $this->_objData = $data;
        return $this->_objData;
    }

    public function updatePreProcess($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        if (UserUtil::isLoggedIn() && $data['uid']) {
            $data['name']  = UserUtil::getVar('uname', $data['uid']);
            $data['email'] = ($data['email'] == '') ? UserUtil::getVar ('email', $data['uid']) : $data['email'];
        }

        $this->_objData = $data;
        return $this->_objData;
    }

    public function update()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $requestURI = $_SERVER['REQUEST_URI'];

        if (strpos($requestURI, 'admin') !== false) {
            $data     = $this->_objData;
            $userObj  = new Newsletter_DBObject_User();
            $userData = $userObj->get($data['id']);
            if (!$userData) {
                LogUtil::registerError(__('No such item found.', $dom));
                return false;
            }
        } else {
            $key = $this->getSelectionKey();
            if (!$key) {
                return false;
            }

            $userObj  = new Newsletter_DBObject_User();
            $userData = $userObj->getUser($key, null, null);
            if (!$userData) {
                LogUtil::registerError(__('No such item found.', $dom));
                return false;
            }
        }

        return parent::update();
    }

    public function countSendedNewsletter($nl_id)
    {
        if ($nl_id > 0) {
            return (int)DBUtil::selectFieldMax('newsletter_users', 'nlu_last_send_nlid', 'COUNT', 'nlu_last_send_nlid = '.$nl_id);
        }
        return false;
    }

    public function countSubscribers()
    {
        return (int)DBUtil::selectFieldMax('newsletter_users', 'nlu_id', 'COUNT', ' nlu_active=1 AND nlu_approved=1');
    }
}
