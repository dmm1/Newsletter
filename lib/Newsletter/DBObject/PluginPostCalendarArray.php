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

class Newsletter_DBObject_PluginPostCalendarArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginPostCalendarArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('PostCalendar');
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
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_PostCalendar_nItems', 1);
        $userNewsletter  = (int)ModUtil::getVar ('Newsletter', 'newsletter_userid', 1);

        if (!SecurityUtil::checkPermission('PostCalendar::', '::', ACCESS_READ, $userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        // eventstatus: APPROVED = 1, QUEUED = 0, HIDDEN = -1, ALLSTATUS = 100
        // sharing: SHARING_PRIVATE = 0, SHARING_PUBLIC = 1, SHARING_GLOBAL = 3
        $sql = "SELECT * FROM postcalendar_events WHERE eventstatus>0 AND sharing>0";
        if ($filtAfterDate) {
            $sql .= " AND ttime>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY eid DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' PostCalendar: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('PostCalendar::Event', $items[$k]['title'].'::'.$items[$k]['eid'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('PostCalendar', 'user', 'display', array('viewtype' => 'event', 'eid' => $items[$k]['eid'], 'newlang' => $lang), null, null, true);
                $items[$k]['nl_content'] = '';
                if ($items[$k]['eventStart']) {
                    $items[$k]['nl_content'] = DateUtil::getDatetime_Date($items[$k]['eventStart']);
                }
                if (empty($items[$k]['nl_content'])) {
                    $items[$k]['nl_content'] = DateUtil::getDatetime_Date($items[$k]['ttime']);
                }
                if ($items[$k]['eventEnd'] && $items[$k]['eventEnd'] != $items[$k]['eventStart']) {
                    $items[$k]['nl_content'] .= ' - ' . DateUtil::getDatetime_Date($items[$k]['eventEnd']);
                }
                if ($items[$k]['hometext']) {
                    $items[$k]['nl_contenttype'] = substr($items[$k]['hometext'], 1, 4); // :text:/:html: => text/html (not used at all)
                    $items[$k]['hometext'] = substr($items[$k]['hometext'], 6);
                    $items[$k]['nl_content'] .= ($items[$k]['nl_content'] ? "<br />\n" : '') . $items[$k]['hometext'];
                }
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
