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


if (!Loader::loadArrayClassFromModule('Newsletter', 'newsletter_data')) {
    return LogUtil::registerError ('Unable to load class [newsletter_data] ... ');
}


class PNShowPreviewArray extends PNNewsletterDataArray
{
    function PNShowPreviewArray($init=null, $where='')
    {
        $this->PNNewsletterDataArray ();
    }
}

