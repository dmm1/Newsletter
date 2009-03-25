<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
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


    function getNewsletterData ($lang=null)
    {
        pnModLangLoad ('Newsletter', 'plugins');

        if (!Loader::loadArrayClassFromModule ('Newsletter', 'plugin_base')) {
            return LogUtil::registerError ('Unable to load array class for [plugin_base]', null, $url);
        }

        $data     = array();
        $enableML = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
        $plugins  = NewsletterUtil::getActivePlugins ();
        $language = FormUtil::getPassedValue ('language', null, 'GETPOST');
        if ($enableML && !$language) {
            return LogUtil::registerError (_NEWSLETTER_LANGUAGE_NOT_SELECTED);
        }

        $data['nItems']   = 0;
        $data['nPlugins'] = count($plugins);
        $data['title']    = pnConfigGetVar('sitename') . ' ' . _NEWSLETTER;
        foreach ($plugins as $plugin) {
            $pluginClassName = 'plugin_' . $plugin;
            if (($class=Loader::loadArrayClassFromModule ('Newsletter', $pluginClassName))) {
                $objArray        = new $class();
                $data[$plugin]   = $objArray->getPluginData ($language);
                $data['nItems'] += (is_array($data[$plugin]) ? count($data[$plugin]) : 1);
            }
	}

	$this->_objData = $data;
	return $this->_objData;
    }


    function getWhere ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        return $this->getNewsletterData (null);
    }


    function getCount ($where='', $doJoin=false)
    {
        return 0;
    }
}

