<?php
/**
 * PageMaster plugin for Newsletter module
 *
 * @copyright © 2010, Mateo Tibaquirá
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

class PNPluginPagemasterArray extends PNPluginBaseArray
{
    function PNPluginPagemasterArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        if (!pnModAvailable('PageMaster') || !pnModDBInfoLoad('PageMaster')) {
            return array();
        }

        return $this->_getpagemasterItems($lang);
    }

    function setPluginParameters()
    {
        // PageMaster TIDs
        $tids = FormUtil::getPassedValue('PagemasterTIDs', array(), 'POST');

        pnModSetVar('Newsletter', 'pagemasterTIDs', array_keys($tids));

        // Additional arguments
        $args = FormUtil::getPassedValue('PagemasterArgs', array(), 'POST');

        pnModSetVar('Newsletter', 'pagemasterArgs', $args);
    }

    function getPluginParameters()
    {
        $pubtypes = null;
        if (pnModAvailable('pagemaster') && pnModDBInfoLoad('pagemaster')) {
			Loader::includeOnce('modules/PageMaster/common.php');
            $pubtypes = PMgetPubType(-1);
        }

        $active = pnModGetVar ('Newsletter', 'pagemasterTIDs', array());
        foreach ($pubtypes as $k => $v) {
            $pubtypes[$k]['nwactive'] = in_array($k, $active);
        }

        $args = pnModGetVar('Newsletter', 'pagemasterArgs', array());

        return array('number' => 1,
                     'param'  => array(
                                       'pmPubTypes'=> $pubtypes,
                                       'pmArgs' => $args
                                      )
                    );
    }

    function _getPagemasterItems($lang)
    {
        $tids = pnModGetVar('Newsletter', 'pagemasterTIDs', array());
        $args = pnModGetVar('Newsletter', 'pagemasterArgs', array());
        $types = array('txt', 'htm');

        $output = array();
        foreach ($tids as $tid) {
			$pubtypeargs = isset($args[$tid]) ? $args[$tid] : array();
			$pubtypeargs['tid'] = $tid;
			$pubtypeargs['countmode'] = 'no';
			foreach ($types as $type) {
			    $output[$type][$tid] = $this->getPagemasterList($pubtypeargs, $type);
			}
		}

        return $output;
    }

	function getPagemasterList($args, $type)
	{
		$args['handlePluginFields'] = 1;

		$list = array();
		switch ($type) {
			case 'txt':
				Loader::includeOnce('modules/PageMaster/common.php');
				$core_title = PMgetPubtypeTitleField($args['tid']);
				$core_title = $core_title ? $core_title : 'id';

				$list = pnModAPIFunc('PageMaster', 'user', 'pubList', $args);
				$list = $list['publist'];
				// fills the core_title for 0.4.2 and pre
				foreach ($list as $k => $v) {
					$list[$k]['core_title'] = $list[$k][$core_title];
				}
				break;
			case 'htm':
				$list = pnModFunc('PageMaster', 'user', 'main', $args);
				break;
		}

		return $list;
	}
}
