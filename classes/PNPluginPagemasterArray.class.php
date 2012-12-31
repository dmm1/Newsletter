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

class PNPluginPagemasterArray extends PNPluginBaseArray
{
    function PNPluginPagemasterArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        if (!ModUtil::available('PageMaster') || !ModUtil::dbInfoLoad('PageMaster')) {
            return array();
        }

        return $this->_getpagemasterItems($lang);
    }

    function setPluginParameters()
    {
        // PageMaster TIDs
        $tids = FormUtil::getPassedValue('PagemasterTIDs', array(), 'POST');

        ModUtil::setVar('Newsletter', 'pagemasterTIDs', array_keys($tids));

        // Additional arguments
        $args = FormUtil::getPassedValue('PagemasterArgs', array(), 'POST');

        ModUtil::setVar('Newsletter', 'pagemasterArgs', $args);
    }

    function getPluginParameters()
    {
        $pubtypes = array();
        if (ModUtil::available('PageMaster') && ModUtil::dbInfoLoad('PageMaster')) {
            Loader::includeOnce('modules/PageMaster/common.php');
            $pubtypes = PMgetPubType(-1);
        }

        $active = ModUtil::getVar ('Newsletter', 'pagemasterTIDs', array());
        foreach ($pubtypes as $k => $v) {
            $pubtypes[$k]['nwactive'] = in_array($k, $active);
        }

        $args = ModUtil::getVar('Newsletter', 'pagemasterArgs', array());

        return array('number' => 1,
                     'param'  => array(
                                       'pmPubTypes'=> $pubtypes,
                                       'pmArgs' => $args
                                      )
                    );
    }

    function _getPagemasterItems($lang)
    {
        $tids = ModUtil::getVar('Newsletter', 'pagemasterTIDs', array());
        $args = ModUtil::getVar('Newsletter', 'pagemasterArgs', array());
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

                $list = ModUtil::apiFunc('PageMaster', 'user', 'pubList', $args);
                $list = $list['publist'];
                // fills the core_title for 0.4.2 and pre
                foreach ($list as $k => $v) {
                    $list[$k]['core_title'] = $list[$k][$core_title];
                }
                break;
            case 'htm':
                $list = ModUtil::func('PageMaster', 'user', 'main', $args);
                break;
        }

        return $list;
    }
}
