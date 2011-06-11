<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2010, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */


class PNUser extends PNObject 
{
    function PNUser ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();

        $this->_objType         = 'newsletter_users';
        $this->_objColumnPrefix = 'nlu';
        $this->_objPath         = 'user';

        $this->_objJoin   = array();
        $this->_objJoin[] = array ( 'join_table'          =>  'users',
                                    'join_field'          =>  array ('uname', 'email'),
                                    'object_field_name'   =>  array ('user_name', 'user_email'), 
                                    'compare_field_table' =>  'uid',
                                    'compare_field_join'  =>  'uid');

        $this->_init ($init, $key, $field);
    }


    function delete ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $id = (int)FormUtil::getPassedValue ('id', 0, 'GET');
            if (!$id) {
                return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
            }
            $where = "nlu_id=$id";
            $ret = DBUtil::deleteWhere ($this->_objType, $where);
        } else {
            $key = $this->getSelectionKey ();
            if (!$key) {
                return false;
            }

            $userObj  = new PNUser ();
            $data     = $userObj->getUser ($key, null, null);
            if (!$data) {
                LogUtil::registerError (__('No such item found.', $dom));
                return false;
            }

            $where = $this->genWhere ($key, null, null);
            if (!$where) {
                return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
            }

            $ret = DBUtil::deleteWhere ($this->_objType, $where);
            if ($ret) {
                LogUtil::registerStatus (__('Your have unsubscribed from our newsletter', $dom));

                $view = Zikula_View::getInstance('Newsletter', false);
                $view->assign ('user_name', $data['uid'] ? UserUtil::getVar('uname', $data['uid']) : $data['name']);
                $view->assign ('user_email', $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email']);
                $view->assign ('site_url', System::getCurrentUrl());
                $message = $view->fetch ('newsletter_email_user_unsubscribe.html');
                $send_from_address = ModUtil::getVar ('Newsletter', 'send_from_address');
                ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email'],
                                                                    'fromaddress'=> $send_from_address,
                                                                    'subject'    => __('Newsletter Subscription Cancelled', $dom),
                                                                    'body'       => $message,
                                                                    'html'       => 1));
            }
        }

        return true;
    }


    function genWhere ($key, $approved=null, $active=null)
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

        return implode (' AND ', $wheres);
    }


    function getDataFromInputPostProcess ($data=null)
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

        $enableML = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        if (!$enableML && !$data['lang']) {
            $data['lang'] = System::getVar('language');
        }

        $limitType = ModUtil::getVar ('Newsletter', 'limit_type', 0);
        if ($limitType) {
            $data['type'] = $limitType;
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function getSelectionKey ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $key = false;
        if (UserUtil::isLoggedIn()) {
            $key = UserUtil::getVar ('uid');
        } else {
            $data = $this->_objData;
            if (!isset($data['email']) || !$data['email']) {
                LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
                return false;
            }
            $key = $data['email'];
        }

        return $key;
    }


    function getUser ($key, $approved=null, $active=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $where = $this->genWhere ($key, $approved, $active);
        if (!$where) {
            return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
        }

        return $this->getWhere ($where);
    }


    function insertPreProcess ($data=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        if (!$data) {
            $data = $this->_objData;
        }

        $auto_approve_registrations = ModUtil::getVar ('Newsletter', 'auto_approve_registrations', 0);
        if ($auto_approve_registrations) {
            $data['approved'] = 1;
        }
        $data['active'] = 1;

        if (UserUtil::isLoggedIn()) {
            if ($data['uid']) {
                $data['name']  = UserUtil::getVar ('uname', $data['uid']);
                $data['email'] = UserUtil::getVar ('email', $data['uid']);
            } else {
                LogUtil::registerError (__('Logic Error: logged-in user does not have his subscription UID set!', $dom));
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function insert ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $data     = $this->_objData;
            $where    = 'nlu_email=\'' . DataUtil::formatForStore($data['email']) . '\'';
            $userObj  = new PNUser ();
            $userData = $userObj->getWhere ($where);
            if ($userData) {
                LogUtil::registerError (__('This email address is already subscribed to our newsletter!', $dom));
                return false;
            }
            return (bool) DBUtil::insertObject ($data, $this->_objType);
        } else {
            $key = $this->getSelectionKey ();
            if (!$key) {
                return false;
            }
            $userObj  = new PNUser ();
            $userData = $userObj->getUser ($key, null, null);
            if ($userData) {
                LogUtil::registerError (__('You are already subscribed to our newsletter!', $dom));
                return false;
            }
        }

        $data = parent::insert ();
        if ($data) {
            LogUtil::registerStatus (__('Your have been subscribed to our newsletter', $dom));

            $view = Zikula_View::getInstance('Newsletter', false);
            $view->assign ('user_name', $data['uid'] ? UserUtil::getVar('uname', $data['uid']) : $data['name']);
            $view->assign ('user_email', $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email']);
            $view->assign ('site_url', System::getCurrentUrl());
            $user_message = $view->fetch ('newsletter_email_user_notify.html');
            $send_from_address = ModUtil::getVar ('Newsletter', 'send_from_address');
            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? UserUtil::getVar('email', $data['uid']) : $data['email'],
                                                                'fromaddress'=> $send_from_address,
                                                                'subject'    => __('Newsletter Subscription Received', $dom),
                                                                'body'       => $user_message,
                                                                'html'       => 1));

            if (ModUtil::getVar('Newsletter', 'notify_admin', 0)) {
                $admin_message = $view->fetch ('newsletter_email_admin_notify.html');
                ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $send_from_address,
                                                                    'fromaddress'=> $send_from_address,
                                                                    'subject'    => __('Newsletter Subscription', $dom),
                                                                    'body'       => $admin_message,
                                                                    'html'       => 1));
            }
        }

        return true;
    }


    function selectPostProcess ($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        // if a uid is set, this is a registered user so we can 
        // take the info fields auto-joined from the users table
        if (isset($data['uid']) && $data['uid']) {
            $data['name']  = $data['user_name'];
            $data['email'] = $data['user_email'];
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function updatePreProcess ($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        if (UserUtil::isLoggedIn() && $data['uid']) {
            $data['name']  = UserUtil::getVar ('uname', $data['uid']);
            $data['email'] = UserUtil::getVar ('email', $data['uid']);
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function update ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $data     = $this->_objData;
            $userObj  = new PNUser ();
            $userData = $userObj->get ($data['id']);
            if (!$userData) {
                LogUtil::registerError (__('No such item found.', $dom));
                return false;
            }
        } else {
            $key = $this->getSelectionKey ();
            if (!$key) {
                return false;
            }

            $userObj  = new PNUser ();
            $userData = $userObj->getUser ($key, null, null);
            if (!$userData) {
                LogUtil::registerError (__('No such item found.', $dom));
                return false;
            }
        }

        return parent::update ();
    }
}

