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

class Newsletter_DBObject_PluginAdvancedPollsArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginAdvancedPollsArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('AdvancedPolls');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (empty($lang)) {
            $lang = System::getVar('language_i18n', 'en');
        }
        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_AdvancedPolls_nItems', 1);
        $userNewsletter  = (int)ModUtil::getVar ('Newsletter', 'newsletter_userid', 1);
        $modinfo = ModUtil::getInfoFromName('AdvancedPolls');
        //$modinfo['version'] = '2.0.1' pn field prefix exist
        //$modinfo['version'] = '3.0.0'

        if (!SecurityUtil::checkPermission('AdvancedPolls::', '::', ACCESS_READ, $userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        if ($modinfo['version'] >= '3.0.0') {
            $datetime = DateUtil::getDatetime();
            $sql = "SELECT * FROM advancedpolls_polls WHERE ('".$datetime."' >= opendate OR opendate IS NULL) AND ('".$datetime."' <= closedate OR closedate = '' OR closedate IS NULL)";
        } else {
            $prefix = System::getVar('prefix');
            $prefix = $prefix ? $prefix.'_' : '';
            $time = time();
            $sql = "SELECT * FROM ".$prefix."advanced_polls_desc WHERE ".$time." >= pn_opendate AND (".$time." <= pn_closedate OR pn_closedate = 0)";
        }
        if ($filtAfterDate) {
            if ($modinfo['version'] >= '3.0.0') {
                $sql .= " AND cr_date>='".$filtAfterDate."'";
            } else {
                $sql .= " AND pn_cr_date>='".$filtAfterDate."'";
            }
        }
        if ($enableML && $lang) {
            if ($modinfo['version'] >= '3.0.0') {
                $sql .= " AND (language='' OR language='".$lang."')";
            } else {
                $sql .= " AND (pn_language='' OR pn_language='".$lang."')";
            }
        }
        if ($modinfo['version'] >= '3.0.0') {
            $sql .= " ORDER BY pollid DESC LIMIT ".$nItems;
        } else {
            $sql .= " ORDER BY pn_pollid DESC LIMIT ".$nItems;
        }
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' AdvancedPolls: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if ($modinfo['version'] < '3.0.0') {
                $items[$k]['pollid'] = $items[$k]['pn_pollid'];
                $items[$k]['title'] = $items[$k]['pn_title'];
                $items[$k]['description'] = $items[$k]['pn_description'];
            }
            if (!SecurityUtil::checkPermission('AdvancedPolls::item', $items[$k]['title'].'::'.$items[$k]['pollid'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('AdvancedPolls', 'user', 'display', array('pollid' => $items[$k]['pollid'], 'newlang' => $lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]['description'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
