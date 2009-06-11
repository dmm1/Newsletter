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


class PNPluginReviewsArray extends PNPluginBaseArray
{
    function PNPluginReviewsArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('Reviews')) {
            return array();
        }

    $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
	$nItems   = pnModGetVar ('Newsletter', 'plugin_Reviews_nItems', 1);
	$params   = array();
	$params['orderBy']  = 'sid ASC';
	$params['numitems'] = $nItems;
	$params['startnum'] = 0;
	$params['ignoreml'] = true;
	if ($enableML && $lang) {
	    $params['ignoreml'] = false;
	    $params['language'] = $lang;
        }
	return pnModAPIFunc('Reviews', 'user', 'getall', $params);
    }
}

