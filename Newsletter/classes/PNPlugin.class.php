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


class PNPlugin extends PNObject 
{
    function PNPlugin ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();
        $this->_objPath = 'plugin';
        $this->_init ($init, $key, $field);
    }

    function save ()
    {
        if (!Loader::loadArrayClassFromModule ('Newsletter', 'plugin_base')) {
            return LogUtil::registerError (__('Unable to load array class for [plugin_base]', $dom), null, $url);
        }

        $pluginClasses = NewsletterUtil::getPluginClasses();

        // save plugins parameters
        foreach ($pluginClasses as $plugin) {
            $pluginClassName = 'plugin_' . $plugin;
            if (($class=Loader::loadArrayClassFromModule ('Newsletter', $pluginClassName))) {
                $objArray        = new $class();
                $objArray->setPluginParameters ();
            }
        }
         $pluginClasses = array_flip($pluginClasses);

        // active plugins
        foreach ($this->_objData as $k=>$dat) {
            if (strpos ($k, '_nItems') === false) {
                pnModSetVar ('Newsletter', 'plugin_'.$k, 1);
            }
            unset ($pluginClasses[$k]);
        }

        // inactive plugins
        foreach ($pluginClasses as $k=>$plugin) {
            pnModSetVar ('Newsletter', 'plugin_'.$k, 0);
        }

        // number of items settings
        foreach ($this->_objData as $k=>$dat) {
            if (strpos ($k, '_nItems') !== false) {
                pnModSetVar ('Newsletter', 'plugin_'.$k, $dat);
            }
        }

        return true;
    }
}

