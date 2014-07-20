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

class Newsletter_NewsletterPlugin_EZComments extends Newsletter_AbstractPlugin
{
    public function getModname()
    {
        return 'EZComments';
    }

    public function getTitle()
    {
        return $this->__('Latest comments');
    }

    public function getDescription()
    {
        return $this->__('Displays a list of the latest comments.');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }

        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        /*$params   = array();
        $params['order']    = 'items DESC';
        $params['numitems'] = $this->nItems;
        $params['startnum'] = 0;
        $params['ignoreml'] = true;
        if ($this->enableML && $lang) {
            $params['ignoreml'] = false;
            $params['language'] = $lang;
        }
        $params['status'] = 0; //Only activated comments (status isn't 'waiting')
        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $params);*/

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM ezcomments WHERE status=0";
        if ($filtAfterDate) {
            $sql .= " AND date>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY date DESC LIMIT ".$this->nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' EZComments: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('EZComments::', $items[$k]['modname'].':'.$items[$k]['objectid'].':', ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } elseif (!SecurityUtil::checkPermission('EZComments::', $items[$k]['modname'].':'.$items[$k]['objectid'].':'.$items[$k]['id'], ACCESS_READ, $this->userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['subject'];

                if (substr($items[$k]['url'], -1, 1) == '/') {
                    // short url: Adding of lang param is not possible.
                    $items[$k]['nl_url_title'] = $items[$k]['url'] . "#comment{$items[$k]['id']}";
                } else {
                    // normal url
                    $items[$k]['nl_url_title'] = $items[$k]['url'] . '&lang='.$this->lang . "#comment{$items[$k]['id']}";
                }
                $items[$k]['nl_content'] = $items[$k]['comment'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
