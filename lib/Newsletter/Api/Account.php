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

class Newsletter_Api_Account extends Zikula_AbstractApi
{
    /**
     * Return an array of items to show in the your account panel
     *
     * @return   array
     */
    public function getall($args)
    {
        $items = array();
        $uname = (isset($args['uname'])) ? $args['uname'] : UserUtil::getVar('uname');

        // does this user exist?
        if (UserUtil::getIdFromName($uname) == false) {
            // user does not exist
            return $items;
        }

        // Create an array of links to return
        if (SecurityUtil::checkPermission('News::', '::', ACCESS_COMMENT)) {
            $items[] = array('url' => ModUtil::url('Newsletter', 'user', 'main'),
                    'module'  => 'Newsletter',
                    'title'   => $this->__('Newsletter'),
                    'icon'    => 'admin.png');

        }

        // Return the items
        return $items;
    }
}
