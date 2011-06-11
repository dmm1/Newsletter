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


function Newsletter_accountapi_getall ($args)
{
    $dom = ZLanguage::getModuleDomain('Newsletter');

    $items = array(
                 array('url'    => ModUtil::url('Newsletter', 'user', 'main'),
                       'module' => 'Newsletter',
                       'set'    => '',
                       'title'  => __('Newsletter', $dom),
                       'icon'   => 'admin.gif')
                 );

    return $items;
}
