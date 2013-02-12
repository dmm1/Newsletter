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

class Newsletter_Controller_Admin extends Zikula_AbstractController
{
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::modifyconfig', '::', ACCESS_ADMIN));

        return $this->redirect(ModUtil::url($this->name, 'admin', 'view', array('ot' => 'statistics')));
    }

    public function settings()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::modifyconfig', '::', ACCESS_ADMIN));

        $form = FormUtil::newForm($this->name, $this);
        return $form->execute('admin/settings.tpl', new Newsletter_Form_Handler_Admin_Settings());
    }

    public function view()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $dPagesize = $this->getVar('pagesize', 25);

        $filter    = FormUtil::getPassedValue('filter', array(), 'GETPOST');
        $format    = FormUtil::getPassedValue('format', null, 'GETPOST');
        $ot        = FormUtil::getPassedValue('ot', null, 'GETPOST');
        $otTarget  = FormUtil::getPassedValue('otTarget', null, 'GETPOST');
        $offset    = FormUtil::getPassedValue('startnum', 0, 'GETPOST');
        $sort      = FormUtil::getPassedValue('sort', null, 'GETPOST');
        $pagesize  = FormUtil::getPassedValue('pagesize', SessionUtil::getVar('pagesize', $dPagesize, '/Newsletter'));
        if ($sort) {
            $filter['sort'] = $sort;
        }
        // do not handle these $ot lists
        if ($ot == 'NewsletterSend') {
            $ot = 'user';
        }

        $url = ModUtil::url('Newsletter', 'user', 'view', array('ot' => $ot));

        if (isset($filter['sort']) && $filter['sort']) {
            // reverse sort order if we sort on the same field again
            $sort    = $filter['sort'];
            $oldSort = SessionUtil::getVar('oldSort', $sort, '/Newsletter');
            $oldOt   = SessionUtil::getVar('oldOt',   $sort, '/Newsletter');
            if ($ot == $oldOt && $sort == $oldSort && strpos ($sort, 'DESC') === false) {
                $sort .= ' DESC';
            }
        }
        if (!$sort) {
            $sort = 'cr_date DESC';
        }

        SessionUtil::setVar('oldSort',  $sort,     '/Newsletter');
        SessionUtil::setVar('oldOt',    $ot,       '/Newsletter');
        SessionUtil::setVar('pagesize', $pagesize, '/Newsletter');

        $data = array();

        if ($ot) {
            $class = 'Newsletter_DBObject_'. ucfirst($ot) . 'Array';
            if (class_exists($class)) {
                $objectArray = new $class();
                if (method_exists($objectArray, 'cleanFilter')) {
                    $filter = $objectArray->cleanFilter($filter);
                }
                $where = $objectArray->genFilter($filter);
                $sort  = $sort ? $sort : $objectArray->_objSort;
                $data  = $objectArray->get($where, $sort, $offset, $pagesize);
                
                $pager = array();
                $pager['numitems']     = $objectArray->getCount($where, true);
                $pager['itemsperpage'] = $pagesize;
                $this->view->assign('startnum', $offset)
                           ->assign('pager', $pager)
                           ->assign('startpage', false);
            }
        }

        if ($ot == 'user') {
            $this->view->assign('filter', $filter);
        }

        //EM Start
        if ($ot == 'plugin') {
            if (method_exists($objectArray, 'getPluginsParameters')) {
                $this->view->assign('plugin_parameters', $objectArray->getPluginsParameters());
            }
        }
        //EM End

        $this->view->assign('ot', $ot)
                   ->assign('objectArray', $data);

        if ($ot == 'ShowPreview') {
            switch ($format) {
                case 1:
                    $content = $this->view->fetch('output/text.tpl');
                    $content = str_replace(array("\n", "\r"), '<br />', $content);
                    break;
                case 2:
                    $content = $this->view->fetch('output/html.tpl');
                    break;
                case 3:
                    $content = $this->view->fetch('output/text_with_link.tpl');
                    $content = str_replace(array("\n", "\r"), '<br />', $content);
                    break;
                default:
                    $content = 'Invalid format [$format] specified...';
            }

            $testsend      = FormUtil::getPassedValue('testsend', 0, 'POST');
            $testsendEmail = FormUtil::getPassedValue('testsend_email', 0, 'POST');
            if ($testsend) {
                $rc = true;
                if (!$testsendEmail) {
                    $rc = LogUtil::registerError($this->__('Your test email was not sent since you did not enter an email address'));
                }
                if (!System::varValidate($testsendEmail, 'email')) {
                    $rc = LogUtil::registerError($this->__('The email address you entered does not seem to be valid'));
                }
                if (!class_exists('Newsletter_DBObject_NewsletterSend')) {
                    $rc = LogUtil::registerError($this->__f('Unable to load class [%s]', 'newsletter_send'), null, $url);
                }

                if ($rc) {
                    $sendObj = new Newsletter_DBObject_NewsletterSend();
                    if ($sendObj->save()) {
                        LogUtil::registerStatus('Test email successfully sent');
                    } else {
                        LogUtil::registerError($this->__('Failure sending the test email'));
                    }
                }
            }
            echo $content;
            exit;
        }

        return $this->view->fetch("admin/view_{$ot}.tpl");
    }

    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $ot  = FormUtil::getPassedValue('ot', 'user', 'GETPOST');
        $otTarget  = FormUtil::getPassedValue('otTarget', $ot, 'GETPOST');
        $id  = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $url = ModUtil::url('Newsletter', 'admin', 'main');

        $class = 'Newsletter_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return LogUtil::registerError($this->__f('Unable to load class for [%s]', $ot), null, $url);
        }

        $object = new $class();
        if ($id) {
            $data = $object->get($id);
            if (!$data) {
                $url = ModUtil::url('Newsletter', 'admin', 'view', array('ot' => $otTarget));
                return LogUtil::registerError($this->__f('Unable to retrieve object of type [%1$s] with id [%2$s].', array($ot, $id)), null, $url);
            }
        } else {
            $data = array();
        }

        $view = Zikula_View::getInstance('Newsletter', false);
        $view->assign('ot', $otTarget);
        $view->assign($otTarget, $data);
        $view->assign('validation', $object->getValidation());

        if($otTarget != 'userimport') {
            $tpl = 'admin/edit_' . $otTarget . '.tpl';
        } else {
         $tpl = 'admin/view_' . $otTarget . '.tpl';
        }
        return $view->fetch($tpl);
    }

    public function save()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $ot  = FormUtil::getPassedValue('ot', 'user', 'GETPOST');
        $otTarget  = FormUtil::getPassedValue('otTarget', $ot, 'GETPOST');
        $id  = (int)FormUtil::getPassedValue('id', 0, 'GETPOST');
        $url = ModUtil::url('Newsletter', 'admin', 'main');

        $class = 'Newsletter_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return LogUtil::registerError($this->__f('Unable to load class for [%s]', $ot), null, $url);
        }

        $object = new $class(DBObject::GET_FROM_POST);

        if ($object->save()) {
            LogUtil::registerStatus($this->__('Done! Item saved.'));
        }

        return System::redirect(ModUtil::url('Newsletter', 'admin', 'view', array('ot' => $otTarget)));
    }

    public function archive_edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $ot  = 'user';
        $id  = (int)FormUtil::getPassedValue ('id', 0, 'GETPOST');
        $url = ModUtil::url('Newsletter', 'admin', 'main');

        $class = 'Newsletter_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return LogUtil::registerError($this->__f('Unable to load class for [%s]', $ot), null, $url);
        }

        $object = new $class();
        if ($id) {
            $data = $object->get($id);
            if (!$data) {
                $url = ModUtil::url('Newsletter', 'admin', 'view', array('ot' => $ot));
                return LogUtil::registerError($this->__f('Unable to retrieve object of type [%1$s] with id [%2$s].', array($ot, $id)), null, $url);
            }
        } else {
            $data = array();
        }

        $this->view->assign('ot', $ot)
                   ->assign($ot, $data)
                   ->assign('validation', $object->getValidation());

        return $this->view->fetch("admin/edit_{$ot}.tpl");
    }

    public function delete()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $ot  = FormUtil::getPassedValue('ot', 'user', 'GETPOST');
        $id  = (int)FormUtil::getPassedValue('id', null, 'GETPOST');
        
        if($ot == 'archive')
            $url = ModUtil::url('Newsletter', 'admin', 'newsletters');
        else
            $url = ModUtil::url('Newsletter', 'admin', 'view', array('ot' => $ot));

        if (!$id && $ot != 'archive') {
            return LogUtil::registerError($this->__('Invalid [id] parameter received.'), null, $url);
        }

        if (!SecurityUtil::validateCsrfToken(FormUtil::getPassedValue('authid', null, 'GETPOST'))) {
            return LogUtil::registerAuthidError($url);
        }

        $class = 'Newsletter_DBObject_'. ucfirst($ot);
        if (!class_exists($class)) {
            return LogUtil::registerError($this->__('Unable to load class [%s]', $ot), null, $url);
        }

        $object = new $class();
        $data   = $object->get($id);
        if (!$data && $ot != 'archive') {
            return LogUtil::registerError($this->__f('Unable to retrieve object of type [%1$s] with id [%2$s].', array($ot, $id)), null, $url);
        }

        $object->delete();
        return $this->redirect($url);
    }

    // Manage archive, recent and new newsletters
    public function newsletters()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $offset     = (int)FormUtil::getPassedValue('startnum', 1, 'GETPOST') - 1;
        $pagesize   = (int)FormUtil::getPassedValue('pagesize', 5, 'GETPOST');

        $objectArray = new Newsletter_DBObject_ArchiveArray();
        $where = '';
        $sort = 'id DESC';
        $data = $objectArray->get($where, $sort, $offset, $pagesize);

        $objArchive  = new Newsletter_DBObject_Archive();
        $objUsers  = new Newsletter_DBObject_User();

        $this->view->assign('objectArray', $data);
        $this->view->assign('LastNewsletter', $data[0]);
        $this->view->assign('newsletterLastidSentCount', $objUsers->countSendedNewsletter($data[0]['id']));
        $this->view->assign('SubscribersCount', $objUsers->countSubscribers());
        $this->view->assign('newsletterNextid', $objArchive->getnextid());
        $this->view->assign('newsletterMaxid', $objArchive->getmaxid());
        $this->view->assign('arraysenddays', Newsletter_Util::getSelectorDataSendDay());
        $pager = array();
        $pager['numitems']     = $objectArray->getCount($where);
        $pager['itemsperpage'] = $pagesize;
        $this->view->assign('startnum', $offset)
                   ->assign('pager', $pager);

        return $this->view->fetch("admin/view_newsletters.tpl");
    }

    // Delete a newsletter from archive and optionally revert Id
    public function deletenewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id  = (int)FormUtil::getPassedValue('id', $args['id'] ? $args['id'] : 0);
        $preserveid  = (int)FormUtil::getPassedValue('preserveid', $args['preserveid'] ? $args['preserveid'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if ($id > 0) {
            $objArchive  = new Newsletter_DBObject_Archive();
            $data = $objArchive->get($id);
            if ($data) {
                if ($objArchive->deletebyid($id)) {
                    LogUtil::registerStatus($this->__('Newsletter successfully deleted, Id ').$id);
                    if ($preserveid) {
                        $objArchive->setnextid($id);
                        LogUtil::registerStatus($this->__('Newsletter next Id set to ').$objArchive->getnextid());
                    }
                } else {
                    LogUtil::registerError($this->__('Error deleting newsletter Id ').$id);
                }
            }
        }

        return System::redirect($url);
    }

    // Create newsletter in archive
    public function createnewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $Nextid  = (int)FormUtil::getPassedValue('Nextid', $args['Nextid'] ? $args['Nextid'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        $objArchive  = new Newsletter_DBObject_Archive();

        // Implement given newsletter Id
        if ($Nextid > 0) {
            $nextAutooincrement = $objArchive->getnextid();
            if ($Nextid != $nextAutooincrement) {
                $maxId = $objArchive->getmaxid();
                if ($Nextid > $maxId) {
                    $objArchive->setnextid($Nextid);
                } else {
                    LogUtil::registerError($this->__('Next newsletter Id have to be grater then ').$maxId);
                    return System::redirect($url);
                }
            }
        }

        // Language - if not set - same sequence as in html.tpl/text.tpl
        $Nllanguage = FormUtil::getPassedValue('language', '', 'GETPOST');
        if (empty($Nllanguage)) {
            $Nllanguage = ZLanguage::getLanguageCode();
        }

        // Get newsletter content
        $nlDataObjectArray = new Newsletter_DBObject_NewsletterDataArray();
        $objNewsletterData  = $nlDataObjectArray->getNewsletterData($Nllanguage);
        $this->view->assign('show_header', '1');
        $this->view->assign('site_url', System::getBaseUrl());
        $this->view->assign('site_name', System::getVar('sitename'));
        $this->view->assign('objectArray', $objNewsletterData);
        $message_html = $this->view->fetch('output/html.tpl');
        $message_text = $this->view->fetch('output/text.tpl');

        // Prepare data
        $archiveData = array();
        $archiveData['date'] = DateUtil::getDatetime();
        $archiveData['lang'] = $this->_objLang;
        $archiveData['lang'] = $Nllanguage;
        $archiveData['n_plugins'] = $objNewsletterData['nPlugins'];
        $archiveData['n_items'] = $objNewsletterData['nItems'];
        $archiveData['text'] = $message_text;
        $archiveData['html'] = $message_html;

        // Create new archive
        $objArchive->setData($archiveData);
        $result = $objArchive->save($archiveData);
        if ($result) {
            $newArchiveId = $result['id'];
            LogUtil::registerStatus($this->__('The new newsletter is added to archive.').' Id: '.$newArchiveId);
        } else {
            LogUtil::registerError($this->__('Error creating newsletter in archive.'));
        }

        return System::redirect($url);
    }

    // Save a newsletter after edit
    public function savenewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id  = (int)FormUtil::getPassedValue('id', $args['id'] ? $args['id'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if ($id > 0) {
            $objArchive  = new Newsletter_DBObject_Archive();
            $data = $objArchive->get($id);
            if ($data) {
                $data['html'] = FormUtil::getPassedValue('html', $args['html'] ? $args['id'] : $data['html']);
                $data['text'] = FormUtil::getPassedValue('text', $args['text'] ? $args['id'] : $data['text']);

                $objArchive->setData($data);
                if ($objArchive->save()) {
                    LogUtil::registerStatus($this->__('Newsletter successfully saved, Id ').$id);
                } else {
                    LogUtil::registerError($this->__('Error saving newsletter Id ').$id);
                }
            }
        }

        return System::redirect($url);
    }

    // Edit a newsletter from archive
    public function editnewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id  = (int)FormUtil::getPassedValue('id', $args['id'] ? $args['id'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if ($id > 0) {
            $objArchive  = new Newsletter_DBObject_Archive();
            $data = $objArchive->get($id);
            if ($data) {
                $this->view->assign('newsletter', $data);

                return $this->view->fetch("admin/edit_newsletter.tpl");
            }
        }

        return System::redirect($url);
    }

    // Send a newsletter from archive
    public function sendnewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id = (int)FormUtil::getPassedValue('id', $args['id'] ? $args['id'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if (!SecurityUtil::validateCsrfToken(FormUtil::getPassedValue('authid', null, 'GETPOST'))) {
            return LogUtil::registerAuthidError($url);
        }

        // Get last archive
        $objArchive  = new Newsletter_DBObject_Archive();
        $dataNewsletter = $objArchive->get($id);
        if ($dataNewsletter) {
            // Determine users to send to
            $where = "(nlu_active=1 AND nlu_approved=1)";
            $enable_multilingual = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
            if ($enable_multilingual) {
                $where = "(nlu_lang='".$dataNewsletter['lang']."' OR nlu_lang='')";
            }
            // not take in account frequency in menual sending
            //$allow_frequency_change = ModUtil::getVar ('Newsletter', 'allow_frequency_change', 0);
            //$default_frequency = ModUtil::getVar ('Newsletter', 'default_frequency', 1);
            $objectUserArray = new Newsletter_DBObject_UserArray();
            $users = $objectUserArray->get($where, 'id');
            // Send object
            $objSend = new Newsletter_DBObject_NewsletterSend();
            $objSend->_objData = $usrids;
            $this->_objSendType = 'manual';
            $objSend->_objUpdateSendDate = true;
            // Scan users
            $alreadysent = 0;
            $nowsent = 0;
            $notsent = 0;
            $newSentTime = DateUtil::getDatetime();
            foreach ($users as $user) {
                if  ($user['last_send_nlid'] == $id) {
                    $alreadysent++;
                } else {
                    // Send to subscriber
                    $user['last_send_nlid'] = $id;
                    if ($user['type'] == 1) {
                        $html = false;
                        $message = $dataNewsletter['text'];
                    } else {
                        $html = true;
                        $message = $dataNewsletter['html'];
                    }
                    if ($objSend->_sendNewsletter($user, $message, $html)) {
                        $nowsent++;
                    } else {
                        $notsent++;
                    }
                }
            }
            LogUtil::registerStatus($this->__('Newsletter successfully send to subscribers: ').$nowsent);
            if ($alreadysent) {
                LogUtil::registerStatus($this->__('Skipped (already sent): ').$alreadysent);
            }
            if ($notsent) {
                LogUtil::registerStatus($this->__('Skipped (not sent for some reason): ').$notsent);
            }
        } else {
                LogUtil::registerError($this->__('Error getting data for newsletter Id ').$id);
        }

        return System::redirect($url);
    }

}
