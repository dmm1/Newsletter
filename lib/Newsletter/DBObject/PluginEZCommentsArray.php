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

class Newsletter_DBObject_PluginEZCommentsArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginEZCommentsArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('EZComments');
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
        $enableML = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        $nItems   = ModUtil::getVar ('Newsletter', 'plugin_EZComments_nItems', 1);

        // this can be setting in future
        // $userNewsletter = 0; this can be default in future, if Zikula core start to accept such parameter in SecurityUtil::checkPermission
        $userNewsletter = 1; // by default userid=1 is for guest, but it is member of Users group in practice. Better then to chow all forums topics
        if (!SecurityUtil::checkPermission('EZComments::', '::', ACCESS_READ, $userNewsletter)) {
            return array();
        }

        /*$params   = array();
        $params['order']    = 'items DESC';
        $params['numitems'] = $nItems;
        $params['startnum'] = 0;
        $params['ignoreml'] = true;
        if ($enableML && $lang) {
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
        $sql .= " ORDER BY date DESC LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' EZComments: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('EZComments::', $items[$k]['modname'].':'.$items[$k]['objectid'].':', ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } elseif (!SecurityUtil::checkPermission('EZComments::', $items[$k]['modname'].':'.$items[$k]['objectid'].':'.$items[$k]['id'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['subject'];
                $items[$k]['nl_url_title'] = $items[$k]['url'].'&newlang='.$lang;
                $items[$k]['nl_content'] = $items[$k]['comment'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
