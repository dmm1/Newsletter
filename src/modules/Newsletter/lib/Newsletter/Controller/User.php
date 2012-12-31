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

class Newsletter_Controller_User extends Zikula_AbstractController
{
    public function main()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW));

        $ot         = FormUtil::getPassedValue('ot', 'main', 'GETPOST');
        $offset     = FormUtil::getPassedValue('startnum', 0, 'GETPOST');
        $pagesize   = FormUtil::getPassedValue('pagesize', ModUtil::getVar ('Newsletter', 'itemsperpage', 30), 'GETPOST');
        $startpage  = isset($args['startpage']) ? 1 : 0;
        $sort       = null;

        $data = array();

        if (($ot && $class = Loader::loadArrayClassFromModule('Newsletter', $ot))) {
            $objectArray = new $class ();
            $where       = $objectArray->genFilter ();
            $sort        = $sort ? $sort : $objectArray->_objSort;
            $data        = $objectArray->get ($where, $sort, $offset, $pagesize);

            $pager = array();
            $pager['numitems']     = $objectArray->getCount ($where);
            $pager['itemsperpage'] = $pagesize;
            $this->view->assign('startnum', $offset)
                       ->assign('pager', $pager);
        }

        $user = null;
        if (Loader::loadClassFromModule('Newsletter', 'user')) {
            $object = new PNUser();
            if (UserUtil::isLoggedIn()) {
                $user = $object->getUser(UserUtil::getVar('uid'));
            }
            $validation = $object->getValidation();
        }

        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
        $this->view->assign('ot', $ot)
                   ->assign('objectArray', $data)
                   ->assign('startpage', $startpage)
                   ->assign('user', $user);

        return $this->view->fetch("user/view_{$ot}.tpl");
    }

    public function detail() // hardcoded for archives
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW));

        $ot  = 'archive';
        $id  = (int)FormUtil::getPassedValue('id', 0);
        $url = ModUtil::url('Newsletter', 'user', 'main');

        if (!$id) {
            return LogUtil::registerError($this->__('Invalid [id] parameter received.'), null, $url);
        }

        if (!($class = Loader::loadClassFromModule('Newsletter', $ot))) {
            return LogUtil::registerError($this->__f('Unable to load class [%s]', $ot), null, $url);
        }

        $obj  = new PNArchive();
        $data = $obj->get($id);

        // just echo text and exit; no need to use template
        print $data['text'];
        exit;
    }

    public function send()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ));

        if (!Loader::loadClassFromModule('Newsletter', 'newsletter_send')) {
            return LogUtil::registerError($this->__('Unable to load class [newsletter_send].'));
        }

        $scheduled = (int)FormUtil::getPassedValue('scheduled', 0);

        $obj = new PNNewsletterSend();

        return $obj->save(array('respond' => 1, 'scheduled' => $scheduled));
    }

    public function edit()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW));

        $ot       = FormUtil::getPassedValue('ot', 'user', 'GETPOST');
        $otTarget = FormUtil::getPassedValue('otTarget', 'main', 'GETPOST');
        $url      = ModUtil::url('Newsletter', 'user', 'main', array('ot'=>$otTarget));

        if (!$ot) {
            return LogUtil::registerError($this->__('Invalid [ot] parameter received'), null, $url);
        }

        if (!SecurityUtil::validateCsrfToken(FormUtil::getPassedValue('authid', null, 'GETPOST'))) {
            return LogUtil::registerAuthidError($url);
        }

        if (!($class = Loader::loadClassFromModule('Newsletter', $ot))) {
            return LogUtil::registerError($this->__('Unable to load class [%s].', $ot), null, $url);
        }

        $object = new $class();
        $data   = $object->getDataFromInput();

        if ($ot == 'main' || $ot == 'user') {
            if (!UserUtil::isLoggedIn()) {
                if (!$data['name']) {
                    return LogUtil::registerError($this->__('Please enter your name'), null, $url);
                }
                if (!$data['email']) {
                    return LogUtil::registerError($this->__('Please enter your email address'), null, $url);
                } elseif (!System::varValidate($data['email'], 'email')) {
                    return LogUtil::registerError($this->__('The email address you entered does not seem to be valid'), null, $url);
                }
            }
            if (!isset($data['tos']) || !$data['tos']) {
                return LogUtil::registerError($this->__('You must accept the terms of service in order to be subscribed!'), null, $url);
            }
        }

        $object->save();

        return System::redirect($url);
    }
}
