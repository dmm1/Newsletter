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

class Newsletter_DBObject_PluginClipArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginClipArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Clip');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }
        if (empty($lang)) {
            $lang = System::getVar('language_i18n', 'en');
        }

        $itemsFull = $this->_getClipItems($lang);

        // Simplify data to be used in template
        $items = array();
        foreach ($itemsFull['txt'] as $itemPublist) {
            foreach ($itemPublist as $Publication) {
                //if ($filtAfterDate && $Publication['core_publishdate'] < $filtAfterDate) {
                if ($filtAfterDate && $Publication['cr_date'] < $filtAfterDate) {
                    // filter by date is given, remove older data
                } else {
                    $items[] = $Publication;
                }
            }
        }

        foreach (array_keys($items) as $k) {
            $items[$k]['nl_title'] = $items[$k]['core_title'];
            $items[$k]['nl_url_title'] = ModUtil::url('Clip', 'user', 'viewpub', array('tid' => $items[$k]['core_tid'], 'pid' => $items[$k]['core_pid'], 'newlang' => $lang, 'fqurl' => true));
            $items[$k]['nl_content'] = $items[$k]['content'];
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
        }

        return $items;
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
        //$types = array('txt', 'htm');
        $types = array('txt');

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
