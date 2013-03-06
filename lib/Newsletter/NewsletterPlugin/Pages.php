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

class Newsletter_NewsletterPlugin_Pages extends Newsletter_AbstractPlugin
{
    public function pluginAvailable()
    {
        return ModUtil::available('Pages');
    }

    public function getPluginTitle()
    {
        return $this->__('Recently added documents');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }
        $this->setLang($lang);

        if (!SecurityUtil::checkPermission('Pages::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM pages WHERE 1";
        if ($filtAfterDate) {
            $sql .= " AND cr_date>='".$filtAfterDate."'";
        }
        if ($this->enableML && $lang) {
            $sql .= " AND (language='' OR language='".$lang."')";
        }
        $sql .= " ORDER BY pageid DESC LIMIT ".$this->nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' Pages: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('Pages:title:', $items[$k]['pageid'].'::', ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('Pages', 'user', 'display', array('pageid' => $items[$k]['pageid'], 'newlang' => $this->lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]['content'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
