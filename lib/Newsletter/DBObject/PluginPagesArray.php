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

class Newsletter_DBObject_PluginPagesArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginPagesArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Pages');
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
        $nItems   = ModUtil::getVar('Newsletter', 'plugin_Pages_nItems', 1);

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM pages WHERE 1";
        if ($filtAfterDate) {
            $sql .= " AND cr_date>='".$filtAfterDate."'";
        }
        if ($enableML && $lang) {
            $sql .= " AND language>='".$lang."'";
        }
        $sql .= " ORDER BY pageid DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Pages: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            $items[$k]['nl_title'] = $items[$k]['title'];
            $items[$k]['nl_url_title'] = ModUtil::url('Pages', 'user', 'display', array('pageid' => $items[$k]['pageid'], 'newlang' => $lang, 'fqurl' => true));
            $items[$k]['nl_content'] = $items[$k]['content'];
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
        }

        return $items;
    }
}

