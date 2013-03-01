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

class Newsletter_Api_User extends Zikula_AbstractApi
{
    public function getlinks()
    {
        $isSubscriber = false;

        if (class_exists('Newsletter_DBObject_User') && UserUtil::isLoggedIn()) {
            $object = new Newsletter_DBObject_User();
            $user = $object->getUser(UserUtil::getVar('uid'));
            if(!empty($user))
                $isSubscriber = true;
        }

        $links = array();

        if(!$isSubscriber && ($this->getVar('allow_anon_registration') || UserUtil::isLoggedIn())) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'main')),
                     'text'  => $this->__('Subscribe'),
                     'class' => 'z-icon-es-ok');
        }

        if($isSubscriber) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'options')),
                     'text'  => $this->__('Settings'),
                     'class' => 'z-icon-es-config');
        }

        if($this->getVar('show_archive') == 1 || ($this->getVar('show_archive') == 2 && UserUtil::isLoggedIn()) || ($this->getVar('show_archive') == 3 && $isSubscriber)) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'archive')),
                     'text'  => $this->__('View Archives'),
                     'class' => 'z-icon-es-preview');
        }

        if(!(!$isSubscriber && UserUtil::isLoggedIn())) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'unsubscribe')),
                     'text'  => $this->__('Unsubscribe'),
                     'class' => 'z-icon-es-cancel');
        }

        $links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main', array('ot' => 'tos')),
                 'text'  => $this->__('Terms of service'),
                 'class' => 'z-icon-es-info');

        if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'main'),
                     'text'  => $this->__('Backend'),
                     'class' => 'z-icon-es-options');
        }

        return $links;
    }
}
