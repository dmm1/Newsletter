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

class Newsletter_DBObject_Plugin extends DBObject 
{
    public function Newsletter_DBObject_Plugin($init='P', $key=null, $field=null)
    {
        $this->_objPath = 'plugin';
        $this->_init($init, $key, $field);
    }

    public function save()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!class_exists('Newsletter_AbstractPlugin')) {
            return LogUtil::registerError(__f('Unable to load class [%s]', 'Newsletter_AbstractPlugin', $dom), null, $url);
        }

        $pluginClasses = Newsletter_Util::getActivePlugins();

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
            $pos = strpos($k, '_nItems');
            if ($pos !== false) {
                ModUtil::setVar('Newsletter', 'plugin_'.$k, $dat);
                // plugin settings
                $pluginname = substr($k, 0, $pos);
                $pluginSettings = $this->_objData[$pluginname.'_Settings0'] .';'. $this->_objData[$pluginname.'_Settings1'];
                ModUtil::setVar('Newsletter', 'plugin_'.$pluginname.'_Settings', $pluginSettings);
            }
        }

        // General filters
         ModUtil::setVar('Newsletter', 'plugins_filtlastdays', FormUtil::getPassedValue('plugins_filtlastdays', 0, 'GETPOST'));
         ModUtil::setVar('Newsletter', 'plugins_filtlastarchive', FormUtil::getPassedValue('plugins_filtlastarchive', 0, 'GETPOST'));

        return true;
    }
}
