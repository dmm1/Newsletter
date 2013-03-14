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

class Newsletter_NewsletterPlugin_Clip extends Newsletter_AbstractPlugin
{
    public function getModname()
    {
        return 'Clip';
    }

    public function getTitle()
    {
        return $this->__('Recently added publications');
    }

    public function getDescription()
    {
        return $this->__('Displays a list of the latest publications.');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        $itemsFull = $this->_getClipItems();

        // Simplify data to be used in template
        $items = array();
        if ($itemsFull) {
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
        }

        foreach (array_keys($items) as $k) {
            $items[$k]['nl_title'] = $items[$k]['core_title'];
            $items[$k]['nl_url_title'] = ModUtil::url('Clip', 'user', 'viewpub', array('tid' => $items[$k]['core_tid'], 'pid' => $items[$k]['core_pid'], 'newlang' => $this->lang), null, null, true);
            $items[$k]['nl_content'] = $items[$k]['content'];
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
        }

        return $items;
    }

    public function setParameters()
    {
        // Clip TIDs
        $tids = $this->getFormValue('TIDs', array(), 'POST');

        $this->setPluginVar('TIDs', array_keys($tids));

        // Additional arguments
        $args = $this->getFormValue('Args', array(), 'POST');

        $this->setPluginVar('Args', $args);
    }

    public function getParameters()
    {
        $pubtypes = array();
        if (ModUtil::available('Clip') && ModUtil::loadApi('Clip')) {
            $pubtypes = Clip_Util::getPubtype(-1)->toArray();
        }

        $active = $this->getPluginVar('TIDs', array());
        foreach ($pubtypes as $k => $v) {
            $pubtypes[$k]['nwactive'] = in_array($k, $active);
        }

        $args = $this->getPluginVar('Args', array());

        return array('number' => 1,
                     'param'  => array(
                                       'PubTypes'=> $pubtypes,
                                       'Args' => $args
                                      )
                    );
    }

    private function _getClipItems()
    {
        $tids = $this->getPluginVar('TIDs', array());
        $args = $this->getPluginVar('Args', array());
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

    private function getClipList($args, $type)
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
