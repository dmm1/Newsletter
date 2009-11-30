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

function getPagemasterPubs($args, $title, $type) {

	$title = $title;
	$output = '';
	if ($args['tid'] == 3){
		$args['template'] = 'eventnewsletter';
		$args['orderby'] = 'event_date';
	}
	if ($args['tid'] == 7)
	$args['template'] = 'newsnewletter';
	if ($args['tid'] == 9)
	$args['template'] = 'verlosungennewsletter';
		
	$args['handlePluginFields'] = 1;
	if ($type == 'getPubFormatted')
	$pubList = pnModFunc('pagemaster', 'user', 'main', $args);
	else
	$pubList = pnModAPIFunc('pagemaster', 'user', 'pubList', $args);
	return $pubList;
}


class PNPluginPagemasterArray extends PNPluginBaseArray
{
    function PNPluginPagemasterArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('pagemaster')) {
            return array();
        }

        if (!pnModDBInfoLoad('pagemaster')) {
            return array();
        }

        return $this->_getpagemasterItems ($lang);
    }


    function setPluginParameters ()
    {
        // pagemaster TIDs
        $tids = FormUtil::getPassedValue ('pagemasterTIDs', null, 'POST');
        if ($tids) {
            pnModSetVar ('Newsletter', 'pagemasterTIDs', implode(',', array_keys($tids)));
        } else {
            pnModSetVar ('Newsletter', 'pagemasterTIDs', '');
        }
    }


    function getPluginParameters ()
    {
        if (pnModAvailable ('pagemaster')) {
            pnModDBInfoLoad('pagemaster');
            $pagemasterPubTypes = DBUtil::selectObjectArray('pagemaster_pubtypes');
        } else {
            $pagemasterPubTypes = null;
        }

        $pagemasterTIDs = pnModGetVar ('Newsletter', 'pagemasterTIDs', '');
        $activepagemasterPlugins = explode(',', $pagemasterTIDs);
        foreach ($pagemasterPubTypes as $k=>$v) {
            $pagemasterPubTypes[$k]['active'] = in_array($v['id'], $activepagemasterPlugins);
        }

        return array ('number'             => 1,
                      'param'              => array('pagemasterPubTypes'=> $pagemasterPubTypes));
    }


    function _getPagemasterItems($lang)
    {

        $rc = true;
        $tids     = pnModGetVar ('Newsletter', 'pagemasterTIDs', '');
        $nItems   = pnModGetVar ('Newsletter', 'plugin_pagemaster_nItems', 1);
        $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
	$args = array ('tid' => 7,'filter' => 'in_newsletter:eq:1');
	$output['tid7']['htm'] = getPagemasterPubs($args, 'news', 'getPubFormatted');
	$output['tid7']['txt'] = getPagemasterPubs($args, 'news', 'getPubTxt');
	$args = array('tid' => 9, 'filter' => 'sys_expire_date:gt:now');
	$output['tid9']['htm'] .= getPagemasterPubs($args, 'verlosungen', 'getPubFormatted');
	$output['tid9']['txt'] .= getPagemasterPubs($args, 'verlosungen', 'getPubTxt');
	$args = array('tid' => 3, 'orderByStr' => 'event_date', 'filter' => 'event_date:gt:now');
	$output['tid3']['htm'] .= getPagemasterPubs($args, 'events', 'getPubFormatted');
	$output['tid3']['txt'] .= getPagemasterPubs($args, 'events', 'getPubTxt');
	return $output;
    }
}

