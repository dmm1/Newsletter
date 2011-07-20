<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2010, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class PNNewsletterDataArray extends PNObjectArray 
{
    function PNNewsletterDataArray($init=null, $where='')
    {
        $this->PNObjectArray();
        $this->_init($init, $where);
    }

    function getNewsletterData($lang=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!Loader::loadArrayClassFromModule ('Newsletter', 'plugin_base')) {
            return LogUtil::registerError(__('Unable to load array class for [plugin_base]', $dom), null, $url);
        }

        $data     = array();
        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $plugins  = NewsletterUtil::getActivePlugins();
        $language = FormUtil::getPassedValue('language', $lang, 'GETPOST');

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

        $data['nItems']   = 0;
        $data['nPlugins'] = count($plugins);
        $data['title']    = System::getVar('sitename') . ' ' . (__('Newsletter', $dom));
        foreach ($plugins as $plugin) {
            $pluginClassName = 'plugin_' . $plugin;
            if ($class = Loader::loadArrayClassFromModule('Newsletter', $pluginClassName)) {
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
        return $this->getNewsletterData (null);
    }

    function getCount($where='', $doJoin=false)
    {
        return 0;
    }
}
