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

class PNPlugin extends DBObject 
{
    function PNPlugin($init='P', $key=null, $field=null)
    {
        
        $this->_objPath = 'plugin';
        $this->_init($init, $key, $field);
    }

    function save()
    {
        if (!Loader::loadArrayClassFromModule ('Newsletter', 'plugin_base')) {
            return LogUtil::registerError(__('Unable to load array class for [plugin_base]', $dom), null, $url);
        }

        $pluginClasses = Newsletter_Util::getPluginClasses();

        // save plugins parameters
        foreach ($pluginClasses as $plugin) {
            $pluginClassName = 'plugin_' . $plugin;
            if ($class = Loader::loadArrayClassFromModule('Newsletter', $pluginClassName)) {
                $objArray = new $class();
                $objArray->setPluginParameters();
            }
        }
        $pluginClasses = array_flip($pluginClasses);

        // active plugins
        foreach ($this->_objData as $k => $dat) {
            if (strpos($k, '_nItems') === false) {
                ModUtil::setVar('Newsletter', 'plugin_'.$k, 1);
            }
            unset($pluginClasses[$k]);
        }

        // inactive plugins
        foreach ($pluginClasses as $k => $plugin) {
            ModUtil::setVar('Newsletter', 'plugin_'.$k, 0);
        }

        // number of items settings
        foreach ($this->_objData as $k => $dat) {
            if (strpos($k, '_nItems') !== false) {
                ModUtil::setVar('Newsletter', 'plugin_'.$k, $dat);
            }
        }

        return true;
    }
}
