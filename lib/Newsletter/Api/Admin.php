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
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'main'),                             'text' => $this->__('Start'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'settings'),                         'text' => $this->__('Newsletter Settings'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'archive'),                          'text' => $this->__('Archive Settings'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'statistics')),  'text' => $this->__('Statistics'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'message')),     'text' => $this->__('Intro Message'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'preview')),     'text' => $this->__('Preview'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'user')),        'text' => $this->__('Subscribers'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'plugin')),      'text' => $this->__('Plugins'));
            $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'userimport')),  'text' => $this->__('Import'));
        }

        return $links;
    }
}
