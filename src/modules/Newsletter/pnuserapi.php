<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
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

