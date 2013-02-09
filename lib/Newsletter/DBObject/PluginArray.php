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

class Newsletter_DBObject_PluginArray extends DBObjectArray 
{
    function Newsletter_DBObject_PluginArray($init=null, $where='')
    {
        $this->_init();
    }

    function getWhere($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $this->_objData = Newsletter_Util::getPluginClasses();
        return $this->_objData;
    }

    function getCount($where='', $doJoin=false)
    {
        return count($this->_objData);
    }

    //EM Start
    function getPluginsParameters()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!class_exists('Newsletter_DBObject_PluginBaseArray')) {
            return LogUtil::registerError (__f('Unable to load array class for [%s]', 'plugin_base', $dom), null, $url);
        }

        $pluginClasses = Newsletter_Util::getPluginClasses();

        $parameters = array();
        // get plugins parameters
        foreach ($pluginClasses as $plugin) {
            $class = 'Newsletter_DBObject_Plugin' . $plugin . 'Array';
            if (class_exists($class)) {
                $objArray = new $class();
                $parameters[$plugin] = $objArray->getPluginParameters ();
            }
        }
        return $parameters;
    }
    //EM End
}
