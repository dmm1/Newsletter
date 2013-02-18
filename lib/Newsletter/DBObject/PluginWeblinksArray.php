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

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!ModUtil::available('Weblinks')) {
            return array();
        }
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_Weblinks_nItems', 1);

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
        $items = $stmt->fetchAll(Doctrine::FETCH_ASSOC);

        return $items;
    }
}
