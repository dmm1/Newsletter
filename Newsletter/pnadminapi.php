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

    pnModLangLoad('Newsletter', 'admin');

    if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'main'),                             'text' => _NEWSLETTER_START);
		$links[] = array('url' => pnModURL('Newsletter', 'admin', 'settings'),                         'text' => _NEWSLETTER_SETTINGS);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'archive'),                          'text' => _NEWSLETTER_VIEWARCHIVES);
		$links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'statistics')),  'text' => _NEWSLETTER_STATISTICS);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'message')),     'text' => _NEWSLETTER_MESSAGEADD);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'preview')),     'text' => _NEWSLETTER_PREVIEW);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'user')),        'text' => _NEWSLETTER_VIEW_SUBSCRIBERS);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'plugin')),      'text' => _NEWSLETTER_VIEW_PLUGINS);
        $links[] = array('url' => pnModURL('Newsletter', 'admin', 'view', array('ot'=>'userimport')),  'text' => _NEWSLETTER_USERIMPORT);
    }

    return $links;
}

