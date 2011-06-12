<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class Newsletter_Api_Admin extends Zikula_AbstractApi
{

	public function getlinks()
	{
    $links = array();

    $dom = ZLanguage::getModuleDomain('Newsletter');

    if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'main'),                             'text' => __('Start', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'settings'),                         'text' => __('Newsletter Settings', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'archive'),                          'text' => __('Archive Settings', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'statistics')),  'text' => __('Statistics', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'message')),     'text' => __('Intro Message', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'preview')),     'text' => __('Preview', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'user')),        'text' => __('Subscribers', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'plugin')),      'text' => __('Plugins', $dom));
        $links[] = array('url' => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'userimport')),  'text' => __('Import', $dom));
    }

    return $links;
}

	public function modifyconfig ()
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Admin rights for this module", $dom));
    }

    $url = ModUtil::url('Newsletter', 'admin', 'settings');

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    $prefs = FormUtil::getPassedValue('preferences', array(), 'POST');

    if ($prefs['limit_type'] && $prefs['default_type'] != $prefs['limit_type']) {
        $prefs['default_type'] = $prefs['limit_type'];
        LogUtil::registerError (__('You have selected to limit the type of newsletter subscriptions but have chosen a different default newsletter type. Your default newsletter type has been set to the value you have selected to limit subscriptions to. Please review your settings!', $dom));
    }

    ModUtil::setVar ('Newsletter', 'admin_key',                  $prefs['admin_key']                  ? $prefs['admin_key']         : substr(md5(time()),-10));
    ModUtil::setVar ('Newsletter', 'allow_anon_registration',    $prefs['allow_anon_registration']    ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'allow_frequency_change',     $prefs['allow_frequency_change']     ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'allow_subscription_change',  $prefs['allow_subscription_change']  ? 1                        : 0);
    ModUtil::setVar ('Newsletter', 'archive_expire',             $prefs['archive_expire']             ? $prefs['archive_expire']    : 0);
    ModUtil::setVar ('Newsletter', 'auto_approve_registrations', $prefs['auto_approve_registrations'] ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'default_frequency',          $prefs['default_frequency']          ? $prefs['default_frequency'] : 0);
    ModUtil::setVar ('Newsletter', 'default_type',               $prefs['default_type']               ? $prefs['default_type']      : 1);
    ModUtil::setVar ('Newsletter', 'enable_multilingual',        $prefs['enable_multilingual']        ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'itemsperpage',               $prefs['itemsperpage']               ? $prefs['itemsperpage']      : 25);
    ModUtil::setVar ('Newsletter', 'limit_type',                 $prefs['limit_type']);
    ModUtil::setVar ('Newsletter', 'max_send_per_hour',          $prefs['max_send_per_hour'] >= 0     ? $prefs['max_send_per_hour'] : 0);
    ModUtil::setVar ('Newsletter', 'notify_admin',               $prefs['notify_admin']               ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'require_tos',                $prefs['require_tos']                ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'show_approval_status',       $prefs['show_approval_status']       ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'disable_auto',               $prefs['disable_auto']               ? 1                          : 0);
    ModUtil::setVar ('Newsletter', 'activate_archive',           $prefs['activate_archive']           ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'personalize_email',          $prefs['personalize_email']          ? 1                           : 0);
    ModUtil::setVar ('Newsletter', 'send_day',                   $prefs['send_day']                   ? $prefs['send_day']          : 5);
    ModUtil::setVar ('Newsletter', 'send_from_address',          $prefs['send_from_address']          ? $prefs['send_from_address'] : System::getVar('adminmail'));
    ModUtil::setVar ('Newsletter', 'newsletter_subject',         $prefs['newsletter_subject']         ? $prefs['newsletter_subject'] : 0);
    ModUtil::setVar ('Newsletter', 'send_per_request',           $prefs['send_per_request'] >= 0      ? $prefs['send_per_request']  : 5);

    return System::redirect($url);
	}

	public function delete ()
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Admin rights for this module", $dom));
    }

    $ot  = 'user';
    $id  = (int)FormUtil::getPassedValue ('id', null, 'GETPOST');
    $url = ModUtil::url('Newsletter', 'admin', 'view', array('ot' => $ot));

    if (!$id) {
        return LogUtil::registerError (__('Invalid [id] parameter received', $dom), null, $url);
    }

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError (__("Unable to load class [$ot]", $dom), null, $url);
    }

    $object = new $class ();
    $data   = $object->get ($id);
    if (!$data) {
        return LogUtil::registerError (__("Unable to retrieve object of type [$ot] with id [$id]", $dom), null, $url);
    }

    $object->delete ();
    return System::redirect($url);
	}

	function Newsletter_adminform_edit()
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Admin rights for this module", $dom));
    }

    $ot       = FormUtil::getPassedValue ('ot', null, 'GETPOST');
    $otTarget = FormUtil::getPassedValue ('otTarget', null, 'GETPOST');
    $filter   = FormUtil::getPassedValue ('filter', null, 'GETPOST');

    if (!$ot) {
        $url = ModUtil::url('Newsletter', 'admin', 'main');
        return LogUtil::registerError (__('Invalid [ot] parameter received', $dom), null, $url);
    }

    $args = array();
    $args['ot'] = ($otTarget ? $otTarget : $ot);
    if ($filter) {
        $args['filter'] = $filter;
    }
    $url = ModUtil::url('Newsletter', 'admin', 'view', $args);

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError (__('Unable to load class [newsletter_util]', $dom), null, $url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError (__("Unable to load class [$ot]", $dom), null, $url);
    }

    $object = new $class ();
    $object->getDataFromInput ();
    $object->save ();
    return System::redirect($url);
	}

	function Newsletter_adminform_modifyarchive ()
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Admin rights for this module", $dom));
    }

    $url = ModUtil::url('Newsletter', 'admin', 'archive');

    if (!SecurityUtil::confirmAuthKey('Newsletter')) {
        return LogUtil::registerAuthidError ($url);
    }

    $prefs = FormUtil::getPassedValue('preferences_archive', array(), 'POST');

    if ($prefs['limit_type'] && $prefs['default_type'] != $prefs['limit_type']) {
        $prefs['default_type'] = $prefs['limit_type'];
        LogUtil::registerError (__('You have selected to limit the type of newsletter subscriptions but have chosen a different default newsletter type. Your default newsletter type has been set to the value you have selected to limit subscriptions to. Please review your settings!', $dom));
    }
    ModUtil::setVar ('Newsletter', 'show_archive', $prefs['show_archive']  ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'create_archive', $prefs['create_archive']  ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_id',      $prefs['show_id']       ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_lang',    $prefs['show_lang']     ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_objects', $prefs['show_objects']  ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_plugins', $prefs['show_plugins']  ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_size',    $prefs['show_size']     ? 1  : 0);
    ModUtil::setVar ('Newsletter', 'show_date',    $prefs['show_date']     ? 1  : 0);

    return System::redirect($url);
	}
}