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
    public function __construct($init=null, $where='')
    {
        $this->_init();
    }

    public function getWhere($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $this->_objData = Newsletter_Util::getPluginClasses();
        return $this->_objData;
    }

    public function getCount($where='', $doJoin=false)
    {
        return count($this->_objData);
    }

    //EM Start
    public function getPluginsParameters()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        if (!class_exists('Newsletter_AbstractPlugin')) {
            return LogUtil::registerError(__f('Unable to load class [%s]', 'Newsletter_AbstractPlugin', $dom), null, $url);
        }

        $pluginClasses = Newsletter_Util::getPluginClasses();

        $parameters = array();
        // get plugins parameters
        foreach ($pluginClasses as $plugin) {
            $class = $plugin;
            if (class_exists($class)) {
                $objArray = new $class();
                $parameters[$plugin] = $objArray->getParameters();
            }
        }
        return $parameters;
    }
    //EM End
}
