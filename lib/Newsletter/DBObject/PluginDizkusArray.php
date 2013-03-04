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

class Newsletter_DBObject_PluginDizkusArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginDizkusArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray ();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Dizkus');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!$this->pluginAvailable()) {
            return array();
        }
        if (empty($lang)) {
            $lang = System::getVar('language_i18n', 'en');
        }

        ModUtil::dbInfoLoad ('Dizkus');
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_Dizkus_nItems', 1);

        // this can be setting in future
        // $userNewsletter = 0; this can be default in future, if Zikula core start to accept such parameter in SecurityUtil::checkPermission
        $userNewsletter = 1; // by default userid=1 is for guest, but it is member of Users group in practice. Better then to chow all forums topics

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT forum_id FROM dizkus_forums WHERE 1";
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Dizkus: ' . $e->getMessage());
        }
        $userforums = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $allowedforums = array();
        foreach (array_keys($userforums) as $k) {
            if (SecurityUtil::checkPermission('Dizkus::', ":".$userforums[$k]['forum_id'].":", ACCESS_READ, $userNewsletter)) {
                $allowedforums[] = $userforums[$k]['forum_id'];
            }
        }
        if (count($allowedforums)==0) {
            // user is not allowed to read any forum at all
            return array();
        }

        $whereforum = ' forum_id IN (' . DataUtil::formatForStore(implode(',', $allowedforums)) . ') ';
        $sql = 'SELECT * FROM dizkus_topics WHERE forum_id IN (' . DataUtil::formatForStore(implode(',', $allowedforums)) . ') ';
        if ($filtAfterDate) {
            $sql .= " AND topic_time>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY topic_id DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Dizkus: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        // get latest posts data
        foreach (array_keys($items) as $k) {
            $sql = 'SELECT * FROM dizkus_posts WHERE post_id='.$items[$k]['topic_last_post_id'];
            $stmt = $connection->prepare($sql);
            try {
                $stmt->execute();
            } catch (Exception $e) {
                return LogUtil::registerError(__('Error in plugin').' Dizkus: ' . $e->getMessage());
            }
            $post = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

            $items[$k]['post_time'] = $post[0]['post_time'];
            $items[$k]['poster_id'] = $post[0]['poster_id'];
            $items[$k]['post_text'] = $post[0]['post_text'];
            $items[$k]['post_title'] = $post[0]['post_title'];
            $items[$k]['username']= UserUtil::getVar('uname', $post[0]['poster_id']);

            $items[$k]['nl_title'] = $items[$k]['topic_title'];
            $items[$k]['nl_url_title'] = ModUtil::url('Dizkus', 'user', 'viewtopic', array('topic' => $items[$k]['topic_id'], 'newlang' => $lang), null, null, true);
            $items[$k]['nl_content'] = $items[$k]['post_text'];
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
        }

        return $items;
    }
}
