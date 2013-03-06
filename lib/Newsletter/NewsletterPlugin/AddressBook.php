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

class Newsletter_NewsletterPlugin_AddressBook extends Newsletter_AbstractPlugin
{
    public function pluginAvailable()
    {
        return ModUtil::available('AddressBook');
    }

    public function getPluginTitle()
    {
        return $this->__('Latest Contacts');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }
        
        $this->setLang($lang);

        if (!SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ, $this->userNewsletter)) {
            return array();
        }

        $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sql = "SELECT * FROM addressbook_address WHERE adr_private=0";
        if ($filtAfterDate) {
            $sql .= " AND adr_cr_date>='".$filtAfterDate."'";
        }
        $sql .= " ORDER BY adr_id DESC LIMIT ".$this->nItems;
        $stmt = $connection->prepare($sql);
        try {
            $stmt->execute();
        } catch (Exception $e) {
            return LogUtil::registerError(__('Error in plugin').' AddressBook: ' . $e->getMessage());
        }
        $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

        foreach (array_keys($items) as $k) {
            $items[$k]['nl_title'] = trim($items[$k]['adr_fname'].' '.$items[$k]['adr_name']);
            if (empty($items[$k]['nl_title'])) {
                $items[$k]['nl_title'] = $items[$k]['adr_company'];
            }
            $items[$k]['nl_url_title'] = ModUtil::url('AddressBook', 'user', 'display', array('id' => $items[$k]['adr_id'], 'newlang' => $this->lang), null, null, true);
            $items[$k]['nl_content'] = '';
            if ($items[$k]['adr_custom_1']) {
                $items[$k]['nl_content'] .= ($items[$k]['nl_content'] ? "<br />\n" : '') . $items[$k]['adr_custom_1'];
            }
            if ($items[$k]['adr_note']) {
                $items[$k]['nl_content'] .= ($items[$k]['nl_content'] ? "<br />\n" : '') . $items[$k]['adr_note'];
            }
            $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            if ($items[$k]['adr_img']) {
                $items[$k]['nl_picture'] = trim($items[$k]['adr_img'], '\/');
                /*$pos = strpos($items[$k]['nl_picture'], 'http');
                if ($pos === false) {
                    $items[$k]['nl_picture'] = System::getBaseUrl().$items[$k]['nl_picture'];
                }*/
            }
        }

        return $items;
    }
}
