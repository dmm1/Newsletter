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
}
