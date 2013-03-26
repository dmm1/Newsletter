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

abstract class Newsletter_AbstractPlugin implements Zikula_TranslatableInterface
{
    /**
     * Plugin name
     *
     * @var string
     */
    protected $name;
    /**
     * Module name
     *
     * @var string
     */
    protected $modname;
    /**
     * Module info
     *
     * @var string
     */
    protected $modinfo;
    /**
     * Plugin name
     *
     * @var string
     */
    protected $displayName;
    /**
     * Title in newsletter
     *
     * @var string
     */
    protected $title;
    /**
     * Description in admin interface
     *
     * @var string
     */
    protected $description;
    /**
     * Plugin status: 1 - active; 0 - inactive
     *
     * @var integer
     */
    protected $nActive;
    /**
     * Max number of items to display
     *
     * @var integer
     */
    protected $nItems;
    /**
     * Display order of this plugin
     *
     * @var integer
     */
    protected $nOrder;
    /**
     * Type of content treatment: 0 - As is; 1 - nl2br; 2 - strip_tags; 3 - strip_tags but img,a
     *
     * @var integer
     */
    protected $nTreat;
    /**
     * Number of chars from content to display (0 - content is ignored)
     *
     * @var integer
     */
    protected $nTruncate;
    /**
     * The user id to use in security checks
     *
     * @var integer
     */
    protected $userNewsletter;
    /**
     * True if multilingual newsletters are activated
     *
     * @var boolean
     */
    protected $enableML;
    
    //Translation
    /**
     * Language
     *
     * @var string
     */
    protected $lang;
    /**
     * Translation domain.
     *
     * @var string
     */
    protected $domain;
    /**
     * Instance of doctrine.entitymanager
     */
    protected $entityManager;

    /**
     * Constructor.
     *
     * @param Zikula_View $view View instance.
     */
    public function __construct($lang=null)
    {
        $class = get_class($this);
        $parts = explode('_', $class);

        ////Plugin name
        $this->name = $parts[2];
        ////Plugin translation domain
        $this->domain = ZLanguage::getModuleDomain($this->getModuleWherePlacedIn());

        ////Modname and modinfo
        //$modname is empty if the function is not available in the plugin class.
        //It defaults to the module the class is located in ($parts[0]).
        //This functionality is only usefull for the Newsletter module itself, providing plugins for other modules.
        $modname = $this->getModname();
        $this->modname = (empty($modname)) ? $parts[0] : $modname;
        //modinfo
        $this->modinfo = ModUtil::getInfoFromName($this->modname);

        ////Display name, title
        //Display name is used in admin interface, defaults to the filename.
        $displayName = $this->getDisplayName();
        $this->displayName = (empty($displayName)) ? $parts[2] : $displayName;
        //Title is used as title in the newsletter, defaults to display name.
        $title = $this->getTitle();
        $this->title = (empty($title)) ? $this->displayName : $title;

        ////Plugin vars
        $this->nActive = (int)ModUtil::getVar('Newsletter', 'plugin_' . $class, 0);
        $this->nItems = (int)ModUtil::getVar('Newsletter', 'plugin_' . $class . '_nItems', 3);
        $arrSettings = Newsletter_Util::getPluginSettingsArray($class); // explode(";", ModUtil::getVar('Newsletter', 'plugin_'.$class.'_Settings', ''));
        $this->nTreat = (int)$arrSettings[0];
        $this->nTruncate = isset($arrSettings[1]) ? (int)$arrSettings[1] : 400;
        $this->nOrder = Newsletter_Util::getPluginOrderFromArray($arrSettings, $this->name);

        ////Module vars
        $this->userNewsletter= (int)ModUtil::getVar('Newsletter', 'newsletter_userid', 1);
        $this->enableML = (bool)ModUtil::getVar('Newsletter', 'enable_multilingual', false);

        ////Language
        if(isset($lang) && empty($lang))
            throw new Zikula_Exception_Fatal('$lang cannot be empty!');
        $this->lang = (isset($lang)) ? $lang : System::getVar('language_i18n', 'en');
        
        //Assign instance of doctrine.entitymanager
        $this->entityManager = ServiceUtil::getManager()->getService('doctrine.entitymanager');
    }
    
    public function pluginAvailable()
    {
        return ModUtil::available($this->modname);
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    //The plugin name has to be the file name!!!
    final public function getName()
    {
        return $this->name;
    }
    
    public function getModname()
    {
        return $this->modname;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    public function getData($filtAfterDate=null)
    {
        return array();
    }

    public function setParameters()
    {
        return;
    }

    public function getParameters()
    {
        return array('number' => 0, 'param' => array());
    }

    public function getSettings()
    {
        return array('nActive' => $this->nActive, 'nItems' => $this->nItems, 'nTreat' => $this->nTreat, 'nTruncate' => $this->nTruncate, 'nOrder' => $this->nOrder);
    }

    public function getModuleWherePlacedIn()
    {
        $class = get_class($this);
        $parts = explode('_', $class);
        return $parts[0];
    }
    
    final protected function getFormValue($key, $default = null, $source = null, $filter = null, array $args = array(), $objectType=null)
    {
        return FormUtil::getPassedValue(get_class($this) . '_' . $key, $default, $source, $filter, $args, $objectType);
    }

    /**
     * The setPluginVar method sets a Newsletter plugin variable.
     *
     * @param string $name    The name of the variable.
     * @param string $value   The value of the variable.
     *
     * @return boolean True if successful, false otherwise.
     *
     * @note You can choose any name you want, your variable will be automatically prefixed.
     * @warning Protected variable names:\n
     * - Settings
     * - nItems
     */
    final protected function setPluginVar($name, $value)
    {
        return ModUtil::setVar('Newsletter', 'plugin_' . get_class($this) . '_' . $name, $value); 
    }
    
    /**
     * The setPluginVars method sets multiple Newsletter plugin variables.
     *
     * @param array $array    The variables array
     *
     * @note You can choose any name you want, your variable will be automatically prefixed.
     * @warning Protected variable names:\n
     * - Settings
     * - nItems
     */
    final protected function setPluginVars($array)
    {
        foreach($array as $var)
        {
            $this->setPluginVar($var['name'], $var['value']);
        }
    }
    
    /**
     * The getPluginVar method gets a Newsletter plugin variable.
     *
     * @param string  $name    The name of the variable.
     * @param boolean $default The value to return if the requested modvar is not set.
     *
     * @return string Newsletter plugin variable value
     */
    final protected function getPluginVar($name, $default=null)
    {
        return ModUtil::getVar('Newsletter', 'plugin_' . get_class($this) . '_' . $name, $default); 
    }

    /**
     * The getPluginVars method gets multiple Newsletter plugin variables.
     */
    final protected function getPluginVars()
    {
        $prefix = 'plugin_' . get_class($this) . '_';

        $vars = ModUtil::getVar('Newsletter');
        foreach($vars as $name => $value)
        {
            if(strpos($name, $prefix) === 0)
                $vars[substr($name, strlen($prefix))] = $value;

            //Remove old variable
            unset($vars[$name]);
        }
        return $vars;
    }

    /**
     * The delPluginVar method deletes a Newsletter plugin variable.
     *
     * Delete a Newsletter plugin module variable.
     *
     * @param string $name    The name of the variable.
     *
     * @return boolean True if successful, false otherwise.
     */
    final protected function delPluginVar($name)
    {
        return ModUtil::delVar('Newsletter', 'plugin_' . get_class($this) . '_' . $name); 
    }

    /**
     * The delPluginVars method deletes ALL Newsletter plugin variables.
     */
    final protected function delPluginVars()
    {
        $vars = $this->getPluginVars();
        foreach($vars as $name => $value)
        {
            $this->delPluginVar($name);
        }
    }

    /**
     * Translate.
     *
     * @param string $msgid String to be translated.
     *
     * @return string The $msgid translated by gettext.
     */
    public function __($msgid)
    {
        return __($msgid, $this->domain);
    }

    /**
     * Translate with sprintf().
     *
     * @param string       $msgid  String to be translated.
     * @param string|array $params Args for sprintf().
     *
     * @return string The $msgid translated by gettext.
     */
    public function __f($msgid, $params)
    {
        return __f($msgid, $params, $this->domain);
    }

    /**
     * Translate plural string.
     *
     * @param string $singular Singular instance.
     * @param string $plural   Plural instance.
     * @param string $count    Object count.
     *
     * @return string Translated string.
     */
    public function _n($singular, $plural, $count)
    {
        return _n($singular, $plural, $count, $this->domain);
    }

    /**
     * Translate plural string with sprintf().
     *
     * @param string       $sin    Singular instance.
     * @param string       $plu    Plural instance.
     * @param string       $n      Object count.
     * @param string|array $params Sprintf() arguments.
     *
     * @return string The $sin or $plu translated by gettext, based on $n.
     */
    public function _fn($sin, $plu, $n, $params)
    {
        return _fn($sin, $plu, $n, $params, $this->domain);
    }
}
