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


function Newsletter_adminapi_getlinks()
{
    $links = array();

    $dom = ZLanguage::getModuleDomain('Newsletter');

    if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'main'),                             'text' => __('Start', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'settings'),                         'text' => __('Newsletter Settings', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'archive'),                          'text' => __('Archive Settings', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'statistics')),  'text' => __('Statistics', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'message')),     'text' => __('Intro Message', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'preview')),     'text' => __('Preview', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'user')),        'text' => __('Subscribers', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'plugin')),      'text' => __('Plugins', $dom));
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'userimport')),  'text' => __('Import', $dom));
    }

    return $links;
}

