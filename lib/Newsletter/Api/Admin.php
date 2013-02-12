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

class Newsletter_Api_Admin extends Zikula_AbstractApi
{
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'statistics')),
                             'text'  => $this->__('Start'),
                             'class' => 'z-icon-es-home');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'settings'),
                             'text'  => $this->__('Settings'),
                             'class' => 'z-icon-es-config');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'message')),
                             'text'  => $this->__('Header Message'));

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'newsletters'),
                             'text'  => $this->__('Newsletters'),
                             'class' => 'z-icon-es-preview');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'user')),
                             'text'  => $this->__('Subscribers'),
                             'class' => 'z-icon-es-user');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'plugin')),
                             'text'  => $this->__('Plugins'));

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'userimport')),
                             'text'  => $this->__('Import'),
                             'class' => 'z-icon-es-import');
        }

        return $links;
    }
}
