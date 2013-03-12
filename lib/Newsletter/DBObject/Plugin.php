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
    public function __construct($init='P', $key=null, $field=null)
    {
        $this->_objPath = 'plugin';
        $this->_init($init, $key, $field);
    }

    public function save()
    {
        $pluginClasses = array_flip(Newsletter_Util::getPluginClasses());

        foreach ($pluginClasses as $k => $plugin) {
            if ($this->_objData[$k]) {
                // active plugin
                ModUtil::setVar('Newsletter', 'plugin_'.$k, 1);
            } else {
                // inactive plugin
                ModUtil::setVar('Newsletter', 'plugin_'.$k, 0);
            }
            // plugin nItems
            ModUtil::setVar('Newsletter', 'plugin_'.$k.'_nItems', $this->_objData[$k.'_nItems']);
            // plugin settings: nTreat, nTruncate, nOrder
            $pluginSettings = $this->_objData[$k.'_nTreat'] .';'. $this->_objData[$k.'_nTruncate'] .';'. $this->_objData[$k.'_nOrder'];
            ModUtil::setVar('Newsletter', 'plugin_'.$k.'_Settings', $pluginSettings);
            // plugin parameters, if any
            if(class_exists($k)) {
                $class = new $k();
                $class->setParameters();
            }
        }

        // General filters
         ModUtil::setVar('Newsletter', 'plugins_filtlastdays', FormUtil::getPassedValue('plugins_filtlastdays', 0, 'GETPOST'));
         ModUtil::setVar('Newsletter', 'plugins_filtlastarchive', FormUtil::getPassedValue('plugins_filtlastarchive', 0, 'GETPOST'));

        return true;
    }
}
