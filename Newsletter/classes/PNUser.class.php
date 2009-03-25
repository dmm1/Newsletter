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
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $id = (int)FormUtil::getPassedValue ('id', 0, 'GET');
            if (!$id) {
                return LogUtil::registerError (_MODARGSERROR);
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
                LogUtil::registerError (_NOSUCHITEM);
                return false;
            }

            $where = $this->genWhere ($key, null, null);
            if (!$where) {
                return LogUtil::registerError (_MODARGSERROR);
            }

            $ret = DBUtil::deleteWhere ($this->_objType, $where);
            if ($ret) {
                LogUtil::registerStatus (_NEWSLETTER_USER_UNSUBSCRIBED);

                $pnRender = pnRender::getInstance('Newsletter', false);
                $pnRender->assign ('user_name', $data['uid'] ? pnUserGetVar('uname', $data['uid']) : $data['name']);
                $pnRender->assign ('user_email', $data['uid'] ? pnUserGetVar('email', $data['uid']) : $data['email']);
                $pnRender->assign ('site_url', pnGetCurrentURL());
                $message = $pnRender->fetch ('newsletter_email_user_unsubscribe.html');
                $send_from_address = pnModGetVar ('Newsletter', 'send_from_address');
                pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? pnUserGetVar('email', $data['uid']) : $data['email'],
                                                                    'fromaddress'=> $send_from_address,
                                                                    'subject'    => _NEWSLETTER_EMAIL_UNSUBSCRIBE_SUBJECT,
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

        if (!isset($data['uid']) && pnUserLoggedIn()) {
            $data['uid'] = pnUserGetVar ('uid');
        }

        $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
        if (!$enableML && !$data['lang']) {
            $data['lang'] = pnConfigGetVar('language');
        }

        $limitType = pnModGetVar ('Newsletter', 'limit_type', 0);
        if ($limitType) {
            $data['type'] = $limitType;
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function getSelectionKey ()
    {
        $key = false;
        if (pnUserLoggedIn()) {
            $key = pnUserGetVar ('uid');
        } else {
            $data = $this->_objData;
            if (!isset($data['email']) || !$data['email']) {
                LogUtil::registerError (_MODARGSERROR);
                return false;
            }
            $key = $data['email'];
        }

        return $key;
    }


    function getUser ($key, $approved=null, $active=null)
    {
        $where = $this->genWhere ($key, $approved, $active);
        if (!$where) {
            return LogUtil::registerError (_MODARGSERROR);
        }

        return $this->getWhere ($where);
    }


    function insertPreProcess ($data=null)
    {
        if (!$data) {
            $data = $this->_objData;
        }

        $auto_approve_registrations = pnModGetVar ('Newsletter', 'auto_approve_registrations', 0);
        if ($auto_approve_registrations) {
            $data['approved'] = 1;
        }
        $data['active'] = 1;

        if (pnUserLoggedIn()) {
            if ($data['uid']) {
                $data['name']  = pnUserGetVar ('uname', $data['uid']);
                $data['email'] = pnUserGetVar ('email', $data['uid']);
            } else {
                LogUtil::registerError ('Logic Error: logged-in user does not have his subscription UID set!');
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function insert ()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $data     = $this->_objData;
            $where    = 'nlu_email=\'' . DataUtil::formatForStore($data['email']) . '\'';
            $userObj  = new PNUser ();
            $userData = $userObj->getWhere ($where);
            if ($userData) {
                LogUtil::registerError (_NEWSLETTER_ALREADYSUBSCRIBED_ADMIN);
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
                LogUtil::registerError (_NEWSLETTER_ALREADYSUBSCRIBED);
                return false;
            }
        }

        $data = parent::insert ();
        if ($data) {
            LogUtil::registerStatus (_NEWSLETTER_USER_SUBSCRIBED);

            $pnRender = pnRender::getInstance('Newsletter', false);
            $pnRender->assign ('user_name', $data['uid'] ? pnUserGetVar('uname', $data['uid']) : $data['name']);
            $pnRender->assign ('user_email', $data['uid'] ? pnUserGetVar('email', $data['uid']) : $data['email']);
            $pnRender->assign ('site_url', pnGetCurrentURL());
            $user_message = $pnRender->fetch ('newsletter_email_user_notify.html');
            $send_from_address = pnModGetVar ('Newsletter', 'send_from_address');
            pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $data['uid'] ? pnUserGetVar('email', $data['uid']) : $data['email'],
                                                                'fromaddress'=> $send_from_address,
                                                                'subject'    => _NEWSLETTER_NOTIFY_USER_SUBJECT,
                                                                'body'       => $user_message,
                                                                'html'       => 1));

            if (pnModGetVar('Newsletter', 'notify_admin', 0)) {
                $admin_message = $pnRender->fetch ('newsletter_email_admin_notify.html');
                pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $send_from_address,
                                                                    'fromaddress'=> $send_from_address,
                                                                    'subject'    => _NEWSLETTER_NOTIFY_ADMIN_SUBJECT,
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

        if (pnUserLoggedIn() && $data['uid']) {
            $data['name']  = pnUserGetVar ('uname', $data['uid']);
            $data['email'] = pnUserGetVar ('email', $data['uid']);
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    function update ()
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        if (strpos($requestURI, 'adminform') !== false) {
            $data     = $this->_objData;
            $userObj  = new PNUser ();
            $userData = $userObj->get ($data['id']);
            if (!$userData) {
                LogUtil::registerError (_NOSUCHITEM);
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
                LogUtil::registerError (_NOSUCHITEM);
                return false;
            }
        }

        return parent::update ();
    }
}

