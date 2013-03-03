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

class Newsletter_DBObject_PluginWeblinksArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginWeblinksArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Weblinks');
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
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_Weblinks_nItems', 1);

        // this can be setting in future
        // $userNewsletter = 0; this can be default in future, if Zikula core start to accept such parameter in SecurityUtil::checkPermission
        $userNewsletter = 1; // by default userid=1 is for guest, but it is member of Users group in practice. Better then to chow all forums topics

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM links_links WHERE status>0";
        if ($filtAfterDate) {
            $sql .= " AND ddate>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY ddate DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Weblinks: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            // get category to check permission
            $stmt = $connection->prepare("SELECT * FROM links_categories WHERE cat_id=".$items[$k]['cat_id']);
            $stmt->execute();
            $category = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);
            if (!SecurityUtil::checkPermission('Weblinks::Link', '::'.$items[$k]['lid'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } elseif (!SecurityUtil::checkPermission('Weblinks::Category', $category['title']."::".$category['cat_id'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = $items[$k]['url'];
                $items[$k]['nl_content'] = $items[$k]['description'];
                $items[$k]['nl_url_readmore'] = ModUtil::url('Weblinks', 'user', 'viewlinkdetails', array('lid' => $items[$k]['lid'], 'newlang' => $lang), null, null, true);
            }
        }

        return $items;
    }
}
