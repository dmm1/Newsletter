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

if (!class_exists('Newsletter_DBObject_NewsletterDataArray')) {
    return LogUtil::registerError(__('Unable to load array class [newsletter_data] ... '));
}

class Newsletter_DBObject_ShowPreviewArray extends Newsletter_DBObject_NewsletterDataArray
{
    function Newsletter_DBObject_ShowPreviewArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_NewsletterDataArray();
    }
}
