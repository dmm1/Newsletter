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

class Newsletter_NewsletterPlugin_Dizkus extends Newsletter_AbstractPlugin
{
    public function getModname()
    {
        return 'Dizkus';
    }

    public function getTitle()
    {
        return $this->__('Latest forum posts');
    }

    public function getDescription()
    {
        return $this->__('Displays a list of the latest forum topics.');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        ModUtil::dbInfoLoad ('Dizkus');

        if (!SecurityUtil::checkPermission('Dizkus::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT forum_id, forum_name FROM dizkus_forums WHERE 1";
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Dizkus: ' . $e->getMessage());
        }
        $userforums = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $allowedforums = array();
        foreach (array_keys($userforums) as $k) {
            if (SecurityUtil::checkPermission('Dizkus::', ":".$userforums[$k]['forum_id'].":", ACCESS_READ, $this->userNewsletter)) {
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
        $sql .= " ORDER BY topic_id DESC LIMIT ".$this->nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Dizkus: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            // get forum name
            foreach ($userforums as $forum) {
                if ($forum['forum_id'] == $items[$k]['forum_id']) {
                    $items[$k]['forum_name'] = $forum['forum_name'];
                }
            }

            // get latest posts data
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
            $items[$k]['nl_url_title'] = ModUtil::url('Dizkus', 'user', 'viewtopic', array('topic' => $items[$k]['topic_id'], 'newlang' => $this->lang), null, null, true);
            $items[$k]['nl_content'] = $items[$k]['forum_name'].', '.$items[$k]['username']."<br />\n".$items[$k]['post_text'];
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
        }

        return $items;
    }
}
