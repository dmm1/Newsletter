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

class Newsletter_DBObject_NewsletterDataArray extends DBObjectArray 
{
    function Newsletter_DBObject_NewsletterDataArray($init=null, $where='')
    {
        $this->_init($init, $where);
    }

    function getNewsletterData($lang=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!class_exists('Newsletter_AbstractPlugin')) {
            return LogUtil::registerError(__f('Unable to load class [%s]', 'Newsletter_AbstractPlugin', $dom), null, $url);
        }

        $data     = array();
        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $plugins  = Newsletter_Util::getActivePlugins();
        $language = (empty($lang)) ? FormUtil::getPassedValue('language', System::getVar('language_i18n', 'en'), 'GETPOST') : $lang; //This is set in case of preview.

        /*
        FIXME: Language management in gettext is quite different, have to process one execution per language now
        if ($language) {
            include_once("modules/Newsletter/pnlang/$language/plugins.php");
        } else {
            pnModLangLoad('Newsletter', 'plugins');
        }

        if ($enableML && !$language) {
            return LogUtil::registerError(__('Please use the language selector in the Filter section to select the language you with to send your newsletter for', $dom));
        }
        */
        
        // General filter parameter: items after after date
        $filtAfterDate = null;
        $filtlastdays = (int)ModUtil::getVar ('Newsletter', 'plugins_filtlastdays', 0);
        $filtlastarchive = (int)ModUtil::getVar ('Newsletter', 'plugins_filtlastarchive', 0);
        if ($filtlastdays > 0) {
            // get date for filtering in format: yyyy-mm-dd hh:mm:ss
            $filtAfterDate = DateUtil::getDatetime_NextDay(-$filtlastdays);
        }
        if ($filtlastarchive) {
            // get last newsletter in archive, date is in same format
            $objectArray = new Newsletter_DBObject_ArchiveArray();
            $dataLastnl = $objectArray->get('', '', 0, 1);
            if ($dataLastnl[0]['date']) {
                if ($filtAfterDate) {
                    $filtAfterDate = max($filtAfterDate, $dataLastnl[0]['date']);
                } else {
                    $filtAfterDate = $dataLastnl[0]['date'];
                }
            }
        }

        $data['nItems']   = 0;
        $data['nPlugins'] = count($plugins);
        $data['title']    = System::getVar('sitename') . ' ' . (__('Newsletter', $dom));
        foreach ($plugins as $plugin) {
            $class = $plugin;

            if (class_exists($class)) {
                $objArray        = new $class($language);
                $data[$plugin]   = $objArray->getPluginData($filtAfterDate);
                $data['nItems'] += (is_array($data[$plugin]) ? count($data[$plugin]) : 1);
            }
        }
        $this->_objData = $data;
        return $this->_objData;
    }

    public function getWhere($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        return $this->getNewsletterData(null);
    }

    public function getCount($where='', $doJoin=false)
    {
        return 0;
    }
}
