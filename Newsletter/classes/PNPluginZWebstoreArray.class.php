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


class PNPluginZWebstoreArray extends PNPluginBaseArray
{
    function PNPluginZWebstoreArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('ZWebstore')) {
            return array();
        }

	pnModDBInfoLoad ('zWebstore');

        if (!Loader::loadClassFromModule('zWebstore', 'webstore_util', false, false, '')) {
            return LogUtil::registerError ('Unable to load class [webstore_util]', null, $url);
        }

        if (!Loader::loadArrayClassFromModule ('zWebstore', 'webstore_base_active')) {
            return LogUtil::registerError ('Unable to load array class for [webstore_base_active]', null, $url);
        }

        if (!Loader::loadArrayClassFromModule ('zWebstore', 'product_newest')) {
            return LogUtil::registerError ('Unable to load array class for [product_newest]', null, $url);
        }

	$nItems = pnModGetVar ('Newsletter', 'plugin_ZWebstore_nItems', 1);
	$objectArray = new PNProductNewestArray ();
	return $objectArray->get (null, null, 0, $nItems);
    }
}

