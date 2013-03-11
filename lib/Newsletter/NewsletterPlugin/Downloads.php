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

class Newsletter_NewsletterPlugin_Downloads extends Newsletter_AbstractPlugin
{

    public function getModname()
    {
        return 'Downloads';
    }

    public function getTitle()
    {
        return $this->__('Latest downloads');
    }
    
    public function getDescription()
    {
        return $this->__('Displays a list of the latest downloads.');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        if (!SecurityUtil::checkPermission('Downloads::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM downloads_downloads WHERE status>0";
        if ($filtAfterDate) {
            $sql .= " AND ddate>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY ddate DESC LIMIT ".$this->nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Downloads: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('Downloads::Item', $items[$k]['lid'].'::', ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } elseif (!SecurityUtil::checkPermission('Downloads::Category', $items[$k]['cid']."::", ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('Downloads', 'user', 'display', array('lid' => $items[$k]['lid'], 'newlang' => $this->lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]['description'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
