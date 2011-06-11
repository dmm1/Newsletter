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


// functions disabled because they're currently incomplete
function Newsletter_userapi_encodeurl($args)
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


function Newsletter_userapi_decodeurl($args)
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

