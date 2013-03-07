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

class Newsletter_NewsletterPlugin_Weblinks extends Newsletter_AbstractPlugin
{

    public function pluginAvailable()
    {
        return ModUtil::available('Weblinks');
    }

    public function getPluginTitle()
    {
        return $this->__('Latest web links');
    }

    public function getModname()
    {
        return 'Weblinks';
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        if (!SecurityUtil::checkPermission('Weblinks::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM links_links WHERE status>0";
        if ($filtAfterDate) {
            $sql .= " AND ddate>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY ddate DESC LIMIT ".$this->nItems;
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
            if (!SecurityUtil::checkPermission('Weblinks::Link', '::'.$items[$k]['lid'], ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } elseif (!SecurityUtil::checkPermission('Weblinks::Category', $category['title']."::".$category['cat_id'], ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = $items[$k]['url'];
                $items[$k]['nl_content'] = $items[$k]['description'];
                $items[$k]['nl_url_readmore'] = ModUtil::url('Weblinks', 'user', 'viewlinkdetails', array('lid' => $items[$k]['lid'], 'newlang' => $this->lang), null, null, true);
            }
        }

        return $items;
    }
}
