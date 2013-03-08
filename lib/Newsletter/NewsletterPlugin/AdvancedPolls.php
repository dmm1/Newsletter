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

class Newsletter_NewsletterPlugin_AdvancedPolls extends Newsletter_AbstractPlugin
{
    public function getModname()
    {
        return 'AdvancedPolls';
    }

    public function getTitle()
    {
        return $this->__('Latest polls');
    }

    public function getDescription()
    {
        return $this->__('Displays a list of the latest polls. Supported are version 2.0.1 and >= 3.0.0.');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        //$this->modinfo['version'] = '2.0.1' pn field prefix exist
        //$this->modinfo['version'] = '3.0.0'

        if (!SecurityUtil::checkPermission('AdvancedPolls::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        if ($this->modinfo['version'] >= '3.0.0') {
            $datetime = DateUtil::getDatetime();
            $sql = "SELECT * FROM advancedpolls_polls WHERE ('".$datetime."' >= opendate OR opendate IS NULL) AND ('".$datetime."' <= closedate OR closedate = '' OR closedate IS NULL)";
        } else {
            $prefix = System::getVar('prefix');
            $prefix = $prefix ? $prefix.'_' : '';
            $time = time();
            $sql = "SELECT * FROM ".$prefix."advanced_polls_desc WHERE ".$time." >= pn_opendate AND (".$time." <= pn_closedate OR pn_closedate = 0)";
        }
        if ($filtAfterDate) {
            if ($this->modinfo['version'] >= '3.0.0') {
                $sql .= " AND cr_date>='".$filtAfterDate."'";
            } else {
                $sql .= " AND pn_cr_date>='".$filtAfterDate."'";
            }
        }
        if ($this->enableML && $this->lang) {
            if ($this->modinfo['version'] >= '3.0.0') {
                $sql .= " AND (language='' OR language='".$this->lang."')";
            } else {
                $sql .= " AND (pn_language='' OR pn_language='".$this->lang."')";
            }
        }
        if ($this->modinfo['version'] >= '3.0.0') {
            $sql .= " ORDER BY pollid DESC LIMIT ".$this->nItems;
        } else {
            $sql .= " ORDER BY pn_pollid DESC LIMIT ".$this->nItems;
        }
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' AdvancedPolls: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if ($this->modinfo['version'] < '3.0.0') {
                $items[$k]['pollid'] = $items[$k]['pn_pollid'];
                $items[$k]['title'] = $items[$k]['pn_title'];
                $items[$k]['description'] = $items[$k]['pn_description'];
            }
            if (!SecurityUtil::checkPermission('AdvancedPolls::item', $items[$k]['title'].'::'.$items[$k]['pollid'], ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('AdvancedPolls', 'user', 'display', array('pollid' => $items[$k]['pollid'], 'newlang' => $this->lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]['description'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
