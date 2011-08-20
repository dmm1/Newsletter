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

class PNPluginClipArray extends PNPluginBaseArray
{
    function PNPluginClipArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        if (!ModUtil::available('Clip') || !ModUtil::dbInfoLoad('Clip')) {
            return array();
        }

        return $this->_getClipItems($lang);
    }

    function setPluginParameters()
    {
        // Clip TIDs
        $tids = FormUtil::getPassedValue('ClipTIDs', array(), 'POST');

        ModUtil::setVar('Newsletter', 'ClipTIDs', array_keys($tids));

        // Additional arguments
        $args = FormUtil::getPassedValue('ClipArgs', array(), 'POST');

        ModUtil::setVar('Newsletter', 'ClipArgs', $args);
    }

    function getPluginParameters()
    {
        $pubtypes = array();
        if (ModUtil::available('Clip') && ModUtil::loadApi('Clip')) {
            $pubtypes = Clip_Util::getPubtype(-1)->toArray();
        }

        $active = ModUtil::getVar ('Newsletter', 'ClipTIDs', array());
        foreach ($pubtypes as $k => $v) {
            $pubtypes[$k]['nwactive'] = in_array($k, $active);
        }

        $args = ModUtil::getVar('Newsletter', 'ClipArgs', array());

        return array('number' => 1,
                     'param'  => array(
                                       'PubTypes'=> $pubtypes,
                                       'Args' => $args
                                      )
                    );
    }

    function _getClipItems($lang)
    {
        $tids = ModUtil::getVar('Newsletter', 'ClipTIDs', array());
        $args = ModUtil::getVar('Newsletter', 'ClipArgs', array());
        $types = array('txt', 'htm');

        $output = array();

        foreach ($tids as $tid) {
            $pubtypeargs = isset($args[$tid]) ? $args[$tid] : array();
            $pubtypeargs['tid'] = $tid;
            $pubtypeargs['countmode'] = 'no';
            foreach ($types as $type) {
                $output[$type][$tid] = $this->getClipList($pubtypeargs, $type);
            }
        }

        return $output;
    }

    function getClipList($args, $type)
    {
        $list = array();

        switch ($type)
        {
            case 'txt':
                $args['handleplugins'] = 1;
                $list = ModUtil::apiFunc('Clip', 'user', 'getall', $args);
                $list = $list ? $list['publist']->toArray() : array();
                break;

            case 'htm':
                $list = ModUtil::func('Clip', 'user', 'list', $args);
                break;
        }

        return $list;
    }
}
