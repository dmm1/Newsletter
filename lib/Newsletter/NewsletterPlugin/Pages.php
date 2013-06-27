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
    public function getModname()
    {
        return 'Pages';
    }

    public function getTitle()
    {
        return $this->__('Recently added documents');
    }

    public function getDescription()
    {
        return $this->__('Displays a list of the latest pages.');
    }


    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        if (!SecurityUtil::checkPermission('Pages::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        //Prefix for old versions
        $prefix = '';
        if($this->modinfo['version'] <= '2.4.2')
            $prefix = 'pn_';

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM pages WHERE 1";
        if ($filtAfterDate) {
            $sql .= " AND {$prefix}cr_date>='".$filtAfterDate."'";
        }
        if ($this->enableML && $this->lang) {
            $sql .= " AND ({$prefix}language='' OR {$prefix}language='".$this->lang."')";
        }
        $sql .= " ORDER BY {$prefix}pageid DESC LIMIT ".$this->nItems;
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
                $items[$k]['nl_title'] = $items[$k]["{$prefix}title"];
                $items[$k]['nl_url_title'] = ModUtil::url('Pages', 'user', 'display', array('pageid' => $items[$k]["{$prefix}pageid"], 'lang' => $this->lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]["{$prefix}content"];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
