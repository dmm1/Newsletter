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


function Newsletter_userform_edit ()
{
    $dom 	  = ZLanguage::getModuleDomain('Newsletter');
    $ot       = FormUtil::getPassedValue ('ot', 'user', 'GETPOST');
    $otTarget = FormUtil::getPassedValue ('otTarget', 'main', 'GETPOST');
    $url      = pnModURL('Newsletter', 'user', 'main', array('ot'=>$otTarget));

    if (!$ot) {
        return LogUtil::registerError (__('Invalid [ot] parameter received', $dom), null, $url);
    }

    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return __("You don't have Overview rights for this module.", $dom);
    }

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError (__("Unable to load class [$ot]", $dom), null, $url);
    }

    $object = new $class ();
    $data = $object->getDataFromInput ();

    if ($ot == 'main' || $ot == 'user') {
        if (!pnUserLoggedIn()) {
            if (!$data['name']) {
                return LogUtil::registerError (__('Please enter your name', $dom), null, $url);
            }
            if (!$data['email']) {
                return LogUtil::registerError (__('Please enter your email address', $dom), null, $url);
        } elseif (!pnVarValidate($data['email'], 'email')) {
                return LogUtil::registerError (__('The email address you entered does not seem to be valid', $dom), null, $url);
            }
        }
        if (!isset($data['tos']) || !$data['tos']) {
            return LogUtil::registerError (__('You must accept the terms of service in order to be subscribed!', $dom), null, $url);
        }
    }

    $object->save ();

    return pnRedirect($url);
}

