<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class Newsletter_Api_User extends Zikula_AbstractApi
{
	public function encodeurl($args)
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    // check we have the required input
    if (!isset($args['modname'])) {
        return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
    }

    if (isset($args['args']['ot'])) {
        return $args['modname'] . '/' . $args['args']['ot'];
    }

    return $args['modname'];
	}


	public function decodeurl($args)
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    // check we actually have some vars to work with...
    if (!isset($args['vars'])) {
        return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
    }

    $args['vars'] = array_slice($args['vars'], 1);

    System::queryStringSetVar ('func', 'main');

    if (isset($args['vars'][1])) {
        System::queryStringSetVar ('ot', $args['vars'][1]);
    }

    return true;
	}
	
	public function edit()
	{
    $dom      = ZLanguage::getModuleDomain('Newsletter');
    $ot       = FormUtil::getPassedValue ('ot', 'user', 'GETPOST');
    $otTarget = FormUtil::getPassedValue ('otTarget', 'main', 'GETPOST');
    $url      = ModUtil::url('Newsletter', 'user', 'main', array('ot'=>$otTarget));

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
        if (!UserUtil::isLoggedIn()) {
            if (!$data['name']) {
                return LogUtil::registerError (__('Please enter your name', $dom), null, $url);
            }
            if (!$data['email']) {
                return LogUtil::registerError (__('Please enter your email address', $dom), null, $url);
        } elseif (!System::varValidate($data['email'], 'email')) {
                return LogUtil::registerError (__('The email address you entered does not seem to be valid', $dom), null, $url);
            }
        }
        if (!isset($data['tos']) || !$data['tos']) {
            return LogUtil::registerError (__('You must accept the terms of service in order to be subscribed!', $dom), null, $url);
        }
    }

    $object->save ();

    return System::redirect($url);
	}
}