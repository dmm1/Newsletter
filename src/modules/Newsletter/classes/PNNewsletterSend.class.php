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

class PNNewsletterSend extends PNObject 
{
    var $_objLang;
    var $_objNewsletterData;
    var $_objSendType;
    var $_objUpdateSendDate;

    function PNNewsletterSend ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();

        $this->_objType           = 'generic';
        $this->_objColumnPrefix   = 'nlu';
        $this->_objPath           = 'user_array';
        $this->_objLang           = null;   // custom var
        $this->_objNewsletterData = null;   // custom var
        $this->_objSendType       = null;   // custom var
        $this->_objUpdateSendDate = null;   // custom var

        $this->_objJoin   = array();
        $this->_objJoin[] = array ( 'join_table'          =>  'users',
                                    'join_field'          =>  array ('uname', 'email'),
                                    'object_field_name'   =>  array ('user_name', 'user_email'), 
                                    'compare_field_table' =>  'uid',
                                    'compare_field_join'  =>  'uid');

        $this->_init ($init, $key, $field);
    }


    // doesn't save user info but allows us to use the standard API through Newsletter_userform_edit()
    function save ($args=array())
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        if (!ModUtil::available('Mailer')) {
            return LogUtil::registerError (__('The mailer module is not available', $dom));
        }

        if (!Loader::loadClassFromModule('Newsletter', 'user')) {
            return LogUtil::registerError (__('Unable to load class [user]', $dom));
        }

        if (!Loader::loadArrayClassFromModule('Newsletter', 'user')) {
            return LogUtil::registerError (__('Unable to load array class [user]', $dom));
        }

        if (!Loader::loadArrayClassFromModule('Newsletter', 'newsletter_data')) {
            return LogUtil::registerError (__('Unable to load array class [newsletter_data]', $dom));
        }

        $enable_multilingual       = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        $this->_objLang            = $enable_multilingual ? FormUtil::getPassedValue ('language', '', 'GETPOST') : SessionUtil::getVar('lang'); // custom var
        $newsletterDataObjectArray = new PNNewsletterDataArray ();
        $this->_objNewsletterData  = $newsletterDataObjectArray->getNewsletterData ($this->_objLang);              // custom var

        $this->_objSendType       = FormUtil::getPassedValue ('sendType', '', 'GETPOST');                          // custom var
        $this->_objUpdateSendDate = FormUtil::getPassedValue ('updateSendDate', '', 'GETPOST');                    // custom var
        $testsend                 = FormUtil::getPassedValue ('testsend', 0, 'GETPOST');                           // from admin->preview

        if (!$this->_objNewsletterData) {
            return LogUtil::registerError (__('No newsletter data to send', $dom));
        }

        if ($testsend) {
            $this->_objSendType = 'test';
            return $this->_sendTest ();
        } 

        if ($this->_objSendType == 'manual') {
            return $this->_sendManual ($args);
        }
		
		if ($this->_objSendType == 'manual_archive') {
            return $this->_sendManual_archive ($args);
        }
     
        $this->_objSendType = 'api';
        return $this->_sendAPI ($args);
    }


    function _sendTest ()
    {
        $testsendEmail = FormUtil::getPassedValue ('testsend_email', 0, 'GETPOST');
        $format        = FormUtil::getPassedValue ('format', 1, 'GETPOST');
        $user = array();
        $user['email'] = $testsendEmail;
        $user['type'] = $format;
        return $this->_sendNewsletter ($user);
    }


    function _sendManual ($args=array())
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $data = $this->_objData;
        if (!$data) {
            return LogUtil::registerError (__('No users were selected to send the newsletter to', $dom));
        }

        $objectArray = new PNUserArray ();
        $userIDs     = implode (',', $data);
        $where       = "nlu_id IN ($userIDs) AND nlu_active=1 AND nlu_approved=1";
        $users       = $objectArray->get ($where, 'id');
        if (!$users) {
            return LogUtil::registerError (__('No users were available to send the newsletter to', $dom));
        }

        $nSent   = 0;
        foreach ($users as $user) {
            if ($this->_sendNewsletter ($user)) {
                $nSent++;
            }
        }

        LogUtil::registerStatus ("$nSent " . (__('Newsletter(s) were successfully sent.', $dom)));
        return true;
    }

	function _sendManual_archive ($args=array()) // send Newsletter & make an archive
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $data = $this->_objData;
        if (!$data) {
            return LogUtil::registerError (__('No users were selected to send the newsletter to', $dom));
        }
		if (!Loader::loadClassFromModule('Newsletter', 'archive')) {
            return LogUtil::registerError (__('Unable to load class [archive]', $dom));
        }
        $objectArray = new PNUserArray ();
        $userIDs     = implode (',', $data);
        $where       = "nlu_id IN ($userIDs) AND nlu_active=1 AND nlu_approved=1";
        $users       = $objectArray->get ($where, 'id');
        if (!$users) {
            return LogUtil::registerError (__('No users were available to send the newsletter to', $dom));
        }
		$thisDay   = date ('w', time());
		$sendPerRequest = ModUtil::getVar ('Newsletter', 'send_per_request', 5);
        
		// check archives for new archive time
        $matched = false;
        $archiveObj = new PNArchive ();
        $archive    = $archiveObj->getRecent ();
        if ($archive) {
            $newArchiveTime = $archive['date'];                        
            $matched = true;
        } else {
            $newArchiveTime = $today;
            $matched = false;
        }
		
        $nSent   = 0;
        foreach ($users as $user) {
            if ($this->_sendNewsletter ($user, $newArchive, $newArchiveTime)) {
                $nSent++;
            }
        }
		
        LogUtil::registerStatus ("$nSent " . (__('Newsletter(s) were successfully sent.', $dom)));
        return true;		
    }
	
    function _sendAPI ($args=array()) // API
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        if (!Loader::loadClassFromModule('Newsletter', 'archive')) {
            return LogUtil::registerError (__('Unable to load class [archive]', $dom));
        }

        // check auth key
        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0));
        $masterKey = (string)ModUtil::getVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            return (__('Invalid admin_key received', $dom));
        }

        // get elegible users
        $objectArray = new PNUserArray ();
        $users = $objectArray->getSendable ($this->_objLang, 'id');
        if (!$users) {
            return (__('No users were available to send the newsletter to', $dom));
        }

        $thisDay   = date ('w', time());
        $scheduled = isset($args['scheduled']) ? $args['scheduled'] : 0;
        $sendAll   = (boolean)FormUtil::getPassedValue ('send_all', 0);  // overrides send_limit per request

        set_time_limit (90);
        ignore_user_abort (true);

        // ensure non-scheduled execution happens on the correct day
        if (!$scheduled){
            $sendDay = ModUtil::getVar ('Newsletter', 'send_day', 0);
            if ($sendDay != $thisDay){        
                return 'Wrong day';
            }
        }
        
        // keep a record of how many mails were sent today
        $maxPerHour = ModUtil::getVar ('Newsletter', 'max_send_per_hour', 0);
        if ($maxPerHour) {
            $spamData  = ModUtil::getVar ('Newsletter', 'spam_count', '');
            if ($spamData) {
                $spamArray = explode ('-', $spamData);
                $now       = time ();
                // if now minus start of send is more than an hour and more than "x" have been sent, stop the process
                // step two: if the hour is greater than when we started, reset counters (and set spam array, used below)
                if ($now-$spamArray['0'] >= 3600 && $spamArray['1'] >= $maxPerHour && date('G')==date('G', $spamArray['0'])) {
                    return 'spam limits encountered';
                } else if (date('G') > date('G', $spamArray['0'])) {
                    ModUtil::setVar ('Newsletter', 'spam_count', time().'-0');
                    $spamArray = array(time(),'0');
                }
            }
        }

        ModUtil::setVar ('Newsletter', 'start_execution_time', (float)array_sum(explode(' ', microtime())));
        $today          = date ('w', time()); 
        $sendPerRequest = ModUtil::getVar ('Newsletter', 'send_per_request', 5);
        
        // check archives for new archive time
        $matched = false;
        $archiveObj = new PNArchive ();
        $archive    = $archiveObj->getRecent ();
        if ($archive) {
            $newArchiveTime = $archive['date'];                        
            $matched = true;
        } else {
            $newArchiveTime = $today;
            $matched = false;
        }

        $nSent = 0;
        $allowFrequencyChange = ModUtil::getVar ('Newsletter', 'allow_frequency_change', 0);
        foreach ($users as $user) {
            if (!$allowFrequencyChange || (isset($user['send_now']) && $user['send_now'])) {        
                if ($this->_sendNewsletter ($user, $newArchiveTime)) {
                    if (++$nSent == $sendPerRequest) {
                        break;
                    }
                }
            }
        }
        LogUtil::registerStatus ("$nSent " . __('Newsletter(s) were successfully sent.', $dom));
        
        if ($maxPerHour) {
            ModUtil::setVar ('Newsletter', 'spam_count', $spamArray['0'] . '-' . ($spamArray['1']+$nSent));
        }

        if (!$matched) {
            $this->_archiveNewsletter ($newArchive, $newArchiveTime);
        }

        ModUtil::setVar ('Newsletter', 'end_execution_time', (float)array_sum(explode(' ', microtime())));
        ModUtil::setVar ('Newsletter', 'end_execution_count', $nSent);
        if (isset($args['respond']) && $args['respond']) {
            return " $nSent ";
        }
        
        return true;
    }


    function _getNewsletterMessage ($user, $cacheID=null, $personalize=false, &$html=0) 
    {
        switch ($user['type']) {
            case 1:  $tpl = 'newsletter_template_text.tpl'; $html = 0; break;
            case 2:  $tpl = 'newsletter_template_html.tpl'; $html = 1; break;
            case 3:  $tpl = 'newsletter_template_text_with_link.tpl'; $html = 0; break; 
            default: $tpl = 'newsletter_template_html.tpl'; $html = 1; break;
        }

        $personalize = ModUtil::getVar('Newsletter','personalize_email', false);
        $view    = Zikula_View::getInstance('Newsletter', $personalize ? false : true, $personalize ? null : $cacheID);
        $view->assign ('show_header', '1');
        $view->assign ('site_url', System::getBaseUrl());
        $view->assign ('site_name', System::getVar('sitename'));
        $view->assign ('user_name', $personalize ? $user['name'] : '');
        $view->assign ('user_email', $personalize ? $user['email'] : '');
        $view->assign ('objectArray', $this->_objNewsletterData);
        return $view->fetch ($tpl);
    }


    function _sendNewsletter ($user, $cacheID=null)
    {
        $html    = false;
        $message = $this->_getNewsletterMessage ($user, $cacheID, false, $html); // defaults to html
        $from    = ModUtil::getVar ('Newsletter', 'send_from_address', System::getVar('adminmail'));        
        $subject = ModUtil::getVar ('Newsletter', 'newsletter_subject') ;
        $sent    = ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array('toaddress'  => $user['email'],
                                                                       'fromaddress'=> $from,
                                                                       'subject'    => $subject,
                                                                       'body'       => $message,
                                                                       'html'       => $html));

        if ($sent && ($this->_objSendType == 'api' || $this->_objUpdateSendDate)) {
            $userData = array();
            $userData['id'] = $user['id'];
            $userData['last_send_date'] = DateUtil::getDatetime ();
            $object = new PNUser ();
            $object->setData ($userData);
            $object->update ();
        }

        return $sent;
    }


    function _archiveNewsletter ($newArchive, $newArchiveTime)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        if (!Loader::loadClassFromModule('Newsletter', 'archive')) {
            return LogUtil::registerError (__('Unable to load array class [archive]', $dom));
        }

        $message = $this->_getNewsletterMessage (array(), null, false);

        $archiveData = array();
        $archiveData['date']      = DateUtil::getDatetime ();
        $archiveData['time']      = $newArchiveTime;
        $archiveData['lang']      = $this->_objLang;
        $archiveData['n_plugins'] = $this->_objNewsletterData['nPlugins'];
        $archiveData['n_items']   = $this->_objNewsletterData['nItems'];
        $archiveData['text']      = $message;
        $archiveObj = new PNArchive ();
        $archiveObj->setData ($archiveData);
        $archiveObj->save ($archiveData);
    }
}

