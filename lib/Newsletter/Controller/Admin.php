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
            if ($ot == 'plugin') {
                $data = Newsletter_Util::getPluginClasses();
                $plugin_parameters = array();
                $plugin_settings = array();
                foreach ($data as $plugin) {
                    if (class_exists($plugin)) {
                        $objPlugin = new $plugin();
                        $plugin_parameters[$plugin] = $objPlugin->getParameters();
                        $plugin_settings[$plugin] = $objPlugin->getSettings();
                    }
                }
                $this->view->assign('plugin_parameters', $plugin_parameters);
                $this->view->assign('plugin_settings', $plugin_settings);
            } else {
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
        }

        if ($ot == 'user') {
            $this->view->assign('filter', $filter);
        }

        $this->view->assign('ot', $ot)
                   ->assign('objectArray', $data);

        if ($ot == 'ShowPreview') {
            $language = FormUtil::getPassedValue('language', System::getVar('language_i18n', 'en'), 'GETPOST');
            ZLanguage::setLocale($language);
            switch ($format) {
                case 1:
                    $content = $this->view->fetch('output/text.tpl');
                    $content = nl2br(strip_tags($content, '<a>'));
                    break;
                case 2:
                    $content = $this->view->fetch('output/'.$this->getVar('template_html', 'html.tpl'));
                    break;
                case 3:
                    $content = $this->view->fetch('output/text_with_link.tpl');
                    $content = nl2br(strip_tags($content, '<a>'));
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

        if ($ot == 'archive') {
            if (FormUtil::getPassedValue('deleteAll', null, 'GETPOST')) {
                $object->delete();
            }
            if (FormUtil::getPassedValue('pruneInPeriod', null, 'GETPOST')) {
                $object->prune();
            }
        } else {
            $object->delete();
        }
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
        if (count($data) == 0) {
            $this->view->assign('LastNewsletter', array());
            $this->view->assign('newsletterLastidSentCount', 0);
        } else {
            $this->view->assign('LastNewsletter', $data[0]);
            $this->view->assign('newsletterLastidSentCount', $objUsers->countSendedNewsletter($data[0]['id']));
        }
        $this->view->assign('SubscribersCount', $objUsers->countSubscribers());
        $this->view->assign('newsletterNextid', $objArchive->getnextid());
        $this->view->assign('newsletterMaxid', $objArchive->getmaxid());
        $this->view->assign('arraysenddays', Newsletter_Util::getSelectorDataSendDay());
        $this->view->assign('archiveExpireSelector', Newsletter_Util::getSelectorDataArchiveExpire());
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

        $id  = (int)FormUtil::getPassedValue('id', (isset($args['id']) && $args['id']) ? $args['id'] : 0);
        $preserveid  = (int)FormUtil::getPassedValue('preserveid', (isset($args['preserveid']) && $args['preserveid']) ? $args['preserveid'] : 0);

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

        $Nextid  = (int)FormUtil::getPassedValue('Nextid', (isset($args['Nextid']) && $args['Nextid']) ? $args['Nextid'] : 0);

        $newArchiveId = ModUtil::apiFunc('Newsletter', 'admin', 'createNewsletter', array(
            'nextId' => $Nextid,
            'language' => FormUtil::getPassedValue('language', '', 'GETPOST')
        ));
        LogUtil::registerStatus($this->__('The new newsletter is added to archive.').' Id: '.$newArchiveId);

        return System::redirect(ModUtil::url('Newsletter', 'admin', 'newsletters'));
    }

    // Save a newsletter after edit
    public function savenewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id  = (int)FormUtil::getPassedValue('id', (isset($args['id']) && $args['id']) ? $args['id'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if ($id > 0) {
            $objArchive  = new Newsletter_DBObject_Archive();
            $data = $objArchive->get($id);
            if ($data) {
                $htmlbody = FormUtil::getPassedValue('htmlbody', (isset($args['htmlbody']) && $args['htmlbody']) ? $args['htmlbody'] : null);
                if (isset($htmlbody)) {
                    // body only editor
                    $partBefore = '';
                    $partAfter = '';
                    $this->getBodyParts($data['html'], $partBefore, $partAfter);
                    $data['html'] = $partBefore ."\n". trim($htmlbody) ."\n". $partAfter;
                } else {
                    $data['html'] = FormUtil::getPassedValue('html', (isset($args['html']) && $args['html']) ? $args['html'] : $data['html']);
                }
                $data['text'] = FormUtil::getPassedValue('text', (isset($args['text']) && $args['text']) ? $args['text'] : $data['text']);

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

        $id  = (int)FormUtil::getPassedValue('id', (isset($args['id']) && $args['id']) ? $args['id'] : 0);
        $useeditor = (int)FormUtil::getPassedValue('useeditor', (isset($args['useeditor']) && $args['useeditor']) ? $args['useeditor'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if ($id > 0) {
            $objArchive  = new Newsletter_DBObject_Archive();
            $data = $objArchive->get($id);
            if ($data) {
                $data['htmlbody'] = trim($this->getBodyParts($data['html']));
                $this->view->assign('newsletter', $data);
                $this->view->assign('useeditor', $useeditor);

                return $this->view->fetch("admin/edit_newsletter.tpl");
            }
        }

        return System::redirect($url);
    }

    // Return body parts
    function getBodyParts($html, &$partBefore = '', &$partAfter = ''){
        $start_tag = "<body";
        $end_tag = "</body>";
        $partInner = $html;
        $pos = strpos($html, $start_tag);
        if ($pos) {
            $start_pos = strpos($html, ">", $pos) + 1;
            $end_pos = strpos($html, $end_tag, $start_pos);
            $partInner = substr($html, $start_pos, $end_pos - $start_pos);
            $partBefore = substr($html, 0, $start_pos);
            $partAfter = substr($html, $end_pos);
        }

        return $partInner;
    }

    // Send a newsletter from archive
    public function sendnewsletter($args = array())
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $id = (int)FormUtil::getPassedValue('id', (isset($args['id']) && $args['id']) ? $args['id'] : 0);
        $send_per_batch = (int)FormUtil::getPassedValue('send_per_batch', (isset($args['send_per_batch']) && $args['send_per_batch']) ? $args['send_per_batch'] : 0);

        $url = ModUtil::url('Newsletter', 'admin', 'newsletters');

        if (!SecurityUtil::validateCsrfToken(FormUtil::getPassedValue('authid', null, 'GETPOST'))) {
            return LogUtil::registerAuthidError($url);
        }

        ModUtil::apiFunc('Newsletter', 'admin', 'sendNewsletter', array(
            'id' => $id,
            'send_per_batch' => $send_per_batch
        ));

        return System::redirect($url);
    }
}
