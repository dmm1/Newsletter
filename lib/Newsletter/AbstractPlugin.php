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
     * Max number of items to display
     *
     * @var integer
     */
    protected $nItems;
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
        $this->nItems = (int)ModUtil::getVar('Newsletter', 'plugin_' . $class . '_nItems', 1);
        
        ////Module vars
        $this->userNewsletter= (int)ModUtil::getVar('Newsletter', 'newsletter_userid', 1);
        $this->enableML = (bool)ModUtil::getVar('Newsletter', 'enable_multilingual', false);
        
        ////Language
        $this->lang = (isset($lang)) ? $lang : System::getVar('language_i18n', 'en');
        ZLanguage::setlocale($this->lang);
        $this->domain = ZLanguage::getModuleDomain($this->modname);
    }
    
    public function pluginAvailable()
    {
        return ModUtil::available($this->modname);
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }
    
    public function getName()
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
        return array ('number' => 0, 'param' => array());
    }

    ////Plugin variables section
    
    final protected function setPluginVar($name, $value)
    {
        ModUtil::setVar('Newsletter', 'plugin_' . get_class($this) . '_' . $name, $value); 
    }
    
    final protected function getPluginVar($name, $default=null)
    {
        return ModUtil::getVar('Newsletter', 'plugin_' . get_class($this) . '_' . $name, $default); 
    }

    ////Translation section
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
