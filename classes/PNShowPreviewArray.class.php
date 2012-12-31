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

if (!Loader::loadArrayClassFromModule('Newsletter', 'newsletter_data')) {
    return LogUtil::registerError(__('Unable to load class [newsletter_data] ... '));
}

class PNShowPreviewArray extends PNNewsletterDataArray
{
    function PNShowPreviewArray($init=null, $where='')
    {
        $this->PNNewsletterDataArray();
    }
}
