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

class Newsletter_DBObject_PluginContentArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginContentArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Content');
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
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_Content_nItems', 1);
        $userNewsletter  = (int)ModUtil::getVar ('Newsletter', 'newsletter_userid', 1);

        if (!SecurityUtil::checkPermission('Content::', '::', ACCESS_READ, $userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM content_page WHERE page_active";
        if ($filtAfterDate) {
            $sql .= " AND page_cr_date>='".$filtAfterDate."'";
        }
        if ($enableML && $lang) {
            $sql .= " AND (page_language='' OR page_language='".$lang."')";
        }
        $sql .= " ORDER BY page_id DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Content: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('Content:page:', $items[$k]['page_id'].'::', ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['page_title'];
                $items[$k]['nl_url_title'] = ModUtil::url('Content', 'user', 'view', array('pid' => $items[$k]['page_id'], 'newlang' => $lang), null, null, true);
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
                // get page content array and collect content
                $items[$k]['nl_content'] = '';
                $content = ModUtil::apiFunc('Content', 'Content', 'getPageContent', array('pageId' => $items[$k]['page_id'], 'editing' => false, 'translate' => false, 'expandContent' => false));
                foreach ($content as $area) {
                    foreach ($area as $arrData) {
                        if ($arrData['data']['text']) {
                            $items[$k]['nl_content'] .= ($items[$k]['nl_content'] ? '<br />' : '') . $arrData['data']['text'];
                        }
                    }
                }
            }
        }

        return $items;
    }
}
