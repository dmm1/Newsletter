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
    $ot       = FormUtil::getPassedValue ('ot', 'user', 'GETPOST');
    $otTarget = FormUtil::getPassedValue ('otTarget', 'main', 'GETPOST');
    $url      = pnModURL('Newsletter', 'user', 'main', array('ot'=>$otTarget));

    if (!$ot) {
        return LogUtil::registerError ('Invalid [ot] parameter received', null, $url);
    }

    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_OVERVIEW);
    }

    if (!SecurityUtil::confirmAuthKey()) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError ("Unable to load class [$ot]", null, $url);
    }

    $object = new $class ();
    $data = $object->getDataFromInput ();

    if ($ot == 'main' || $ot == 'user') {
        if (!pnUserLoggedIn()) {
            if (!$data['name']) {
                return LogUtil::registerError (_NEWSLETTER_NAME_NOT_SUPPLIED, null, $url);
            }
            if (!$data['email']) {
                return LogUtil::registerError (_NEWSLETTER_EMAIL_NOT_SUPPLIED, null, $url);
	    } elseif (!pnVarValidate($data['email'], 'email')) {
                return LogUtil::registerError (_NEWSLETTER_EMAIL_INVALID, null, $url);
	    }
	}
        if (!isset($data['tos']) || !$data['tos']) {
            return LogUtil::registerError (_NEWSLETTER_TOS_NOT_SELECTED, null, $url);
	}
    }

    $object->save ();

    return pnRedirect($url);
}

