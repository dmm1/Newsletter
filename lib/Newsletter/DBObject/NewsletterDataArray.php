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
        if (!class_exists('Newsletter_DBObject_PluginBaseArray')) {
            return LogUtil::registerError(__('Unable to load array class for [plugin_base]'), null, $url);
        }

        $data     = array();
        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $plugins  = Newsletter_Util::getActivePlugins();
        $language = FormUtil::getPassedValue('language', $lang, 'GETPOST');

        /*
        FIXME: Language management in gettext is quite different, have to process one execution per language now
        if ($language) {
            include_once("modules/Newsletter/pnlang/$language/plugins.php");
        } else {
            pnModLangLoad('Newsletter', 'plugins');
        }

        if ($enableML && !$language) {
            return LogUtil::registerError(__('Please use the language selector in the Filter section to select the language you with to send your newsletter for'));
        }
        */

        $data['nItems']   = 0;
        $data['nPlugins'] = count($plugins);
        $data['title']    = System::getVar('sitename') . ' ' . (__('Newsletter'));
        foreach ($plugins as $plugin) {
            $class = 'Newsletter_DBObject_Plugin' . $plugin . 'Array';
            if (class_exists($class)) {
                $objArray        = new $class();
                $data[$plugin]   = $objArray->getPluginData($language);
                $data['nItems'] += (is_array($data[$plugin]) ? count($data[$plugin]) : 1);
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }

    function getWhere($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        return $this->getNewsletterData(null);
    }

    function getCount($where='', $doJoin=false)
    {
        return 0;
    }
}
