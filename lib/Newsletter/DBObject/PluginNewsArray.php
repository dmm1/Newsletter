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

class Newsletter_DBObject_PluginNewsArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginNewsArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('News');
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
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_News_nItems', 1);
        $userNewsletter  = (int)ModUtil::getVar ('Newsletter', 'newsletter_userid', 1);

        if (!SecurityUtil::checkPermission('News::', '::', ACCESS_READ, $userNewsletter)) {
            return array();
        }

        $modvars = ModUtil::getVar('News');
        $storyorder = $modvars['storyorder'];
        switch ($storyorder)
        {
            case 0:
                $sort = "sid DESC";
                break;
            case 2:
                $sort = "weight ASC";
                break;
            case 1:
            default:
                $sort = "ffrom DESC";
        }
        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM news WHERE published_status=0";
        if ($filtAfterDate) {
            $sql .= " AND ffrom>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY ".$sort." LIMIT ".$nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' News: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            if (!SecurityUtil::checkPermission('News::', $items[$k]['cr_uid'].'::'.$items[$k]['sid'], ACCESS_READ, $userNewsletter)) {
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('News', 'user', 'display', array('sid' => $items[$k]['sid'], 'newlang' => $lang), null, null, true);
                $items[$k]['nl_content'] = $items[$k]['hometext'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
                if ($modvars['picupload_enabled'] && $items[$k]['pictures'] > 0) {
                    $items[$k]['nl_picture'] = $modvars['picupload_uploaddir'].'/pic_sid'.$items[$k]['sid'].'-0-thumb2.jpg';
                }
            }
        }

        return $items;
    }
}
