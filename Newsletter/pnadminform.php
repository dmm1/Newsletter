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


function Newsletter_adminform_modifyconfig ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_ADMIN);
    }

    $url = pnModURL('Newsletter', 'admin', 'main');

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    $prefs = FormUtil::getPassedValue('preferences', array(), 'POST');

    if ($prefs['limit_type'] && $prefs['default_type'] != $prefs['limit_type']) {
        $prefs['default_type'] = $prefs['limit_type'];
        LogUtil::registerError (_NEWSLETTER_LIMIT_DEFAULT_TYPE_MISMATCH);
    }

    pnModSetVar ('Newsletter', 'admin_key',                  $prefs['admin_key']                  ? $prefs['admin_key']         : substr(md5(time()),-10));
    pnModSetVar ('Newsletter', 'allow_anon_registration',    $prefs['allow_anon_registration']    ? 1                           : 0);
    pnModSetVar ('Newsletter', 'allow_frequency_change',     $prefs['allow_frequency_change']     ? 1                           : 0);
    pnModSetVar ('Newsletter', 'archive_expire',             $prefs['archive_expire']             ? $prefs['archive_expire']    : 0);
    pnModSetVar ('Newsletter', 'auto_approve_registrations', $prefs['auto_approve_registrations'] ? 1                           : 0);
    pnModSetVar ('Newsletter', 'default_frequency',          $prefs['default_frequency']          ? $prefs['default_frequency'] : 0);
    pnModSetVar ('Newsletter', 'default_type',               $prefs['default_type']               ? $prefs['default_type']      : 1);
    pnModSetVar ('Newsletter', 'enable_multilingual',        $prefs['enable_multilingual']        ? 1                           : 0);
    pnModSetVar ('Newsletter', 'itemsperpage',               $prefs['itemsperpage']               ? $prefs['itemsperpage']      : 25);
    pnModSetVar ('Newsletter', 'limit_type',                 $prefs['limit_type']);
    pnModSetVar ('Newsletter', 'max_send_per_hour',          $prefs['max_send_per_hour'] >= 0     ? $prefs['max_send_per_hour'] : 0);
    pnModSetVar ('Newsletter', 'notify_admin',               $prefs['notify_admin']               ? 1                           : 0);
    pnModSetVar ('Newsletter', 'require_tos',                $prefs['require_tos']               ? 1                           : 0);
    pnModSetVar ('Newsletter', 'show_approval_status',   	 $prefs['show_approval_status']  ? 1                           : 0);
	pnModSetVar ('Newsletter', 'disable_auto',   	 		 $prefs['disable_auto']  ? 1                           : 0);
	pnModSetVar ('Newsletter', 'activate_archive',           $prefs['activate_archive']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'personalize_email',          $prefs['personalize_email']          ? 1                           : 0);
    pnModSetVar ('Newsletter', 'send_day',                   $prefs['send_day']                   ? $prefs['send_day']          : 5);
    pnModSetVar ('Newsletter', 'send_from_address',          $prefs['send_from_address']          ? $prefs['send_from_address'] : pnConfigGetVar('adminmail'));
    pnModSetVar ('Newsletter', 'newsletter_subject',         $prefs['newsletter_subject']          ? $prefs['newsletter_subject'] : 0);
	pnModSetVar ('Newsletter', 'send_per_request',           $prefs['send_per_request'] >= 0      ? $prefs['send_per_request']  : 5);

    return pnRedirect($url);
}


function Newsletter_adminform_delete ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_ADMIN);
    }

    $ot  = 'user';
    $id  = (int)FormUtil::getPassedValue ('id', null, 'GETPOST');
    $url = pnModURL('Newsletter', 'admin', 'view', array('ot' => $ot));

    if (!$id) {
        return LogUtil::registerError ('Invalid [id] parameter received', null, $url);
    }

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError ("Unable to load class [$ot]", null, $url);
    }

    $object = new $class ();
    $data   = $object->get ($id);
    if (!$data) {
        return LogUtil::registerError ("Unable to retrieve object of type [$ot] with id [$id]", null, $url);
    }

    $object->delete ();
    return pnRedirect($url);
}


function Newsletter_adminform_edit ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_ADMIN);
    }

    $ot       = FormUtil::getPassedValue ('ot', null, 'GETPOST');
    $otTarget = FormUtil::getPassedValue ('otTarget', null, 'GETPOST');
    $filter   = FormUtil::getPassedValue ('filter', null, 'GETPOST');

    if (!$ot) {
        $url = pnModURL('Newsletter', 'admin', 'main');
        return LogUtil::registerError ('Invalid [ot] parameter received', null, $url);
    }

    $args = array();
    $args['ot'] = ($otTarget ? $otTarget : $ot);
    if ($filter) {
        $args['filter'] = $filter;
    }
    $url = pnModURL('Newsletter', 'admin', 'view', $args);

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError ('Unable to load class [newsletter_util]', null, $url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError ("Unable to load class [$ot]", null, $url);
    }

    $object = new $class ();
    $object->getDataFromInput ();
    $object->save ();
    return pnRedirect($url);
}
function Newsletter_adminform_modifyarchive ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_ADMIN);
    }

    $url = pnModURL('Newsletter', 'admin', 'archive');

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    $prefs = FormUtil::getPassedValue('preferences_archive', array(), 'POST');

    if ($prefs['limit_type'] && $prefs['default_type'] != $prefs['limit_type']) {
        $prefs['default_type'] = $prefs['limit_type'];
        LogUtil::registerError (_NEWSLETTER_LIMIT_DEFAULT_TYPE_MISMATCH);
    }
	pnModSetVar ('Newsletter', 'show_archive',    $prefs['show_archive']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'show_id',    $prefs['show_id']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'show_lang',    $prefs['show_lang']               ? 1                           : 0);
    pnModSetVar ('Newsletter', 'show_objects',    $prefs['show_objects']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'show_plugins',    $prefs['show_plugins']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'show_size',    $prefs['show_size']               ? 1                           : 0);
	pnModSetVar ('Newsletter', 'show_date',    $prefs['show_date']               ? 1                           : 0);
	
	return pnRedirect($url);
}

