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
     * Translation domain.
     *
     * @var string
     */
    protected $domain;
    /**
     * Instance of Zikula_View.
     *
     * @var Zikula_View
     */
    protected $view;
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
    /**
     * Plugin name
     *
     * @var string
     */
    protected $pluginname;
    /**
     * Language
     *
     * @var string
     */
    protected $lang;

    /**
     * Constructor.
     *
     * @param Zikula_View $view View instance.
     */
    public function __construct()
    {
        $parts = explode('_', get_class($this));

        $this->modname = $parts[0];
        $this->pluginname = $parts[2];
        $this->nItems = (int)ModUtil::getVar('Newsletter', 'plugin_' . $this->pluginname . '_nItems', 1);
        $this->userNewsletter= (int)ModUtil::getVar('Newsletter', 'newsletter_userid', 1);
        $this->enableML = (bool)ModUtil::getVar('Newsletter', 'enable_multilingual', false);
        $this->modinfo = ModUtil::getInfoFromName($this->modname);
        $this->lang = System::getVar('language_i18n', 'en');
        
        $this->domain = ZLanguage::getModuleDomain($this->modname);
    }

    public function setLang($lang)
    {
        if(isset($lang))
            $this->lang = $lang;
    }
    
    public function pluginAvailable()
    {
        return false;
    }
    
    public function getPluginName()
    {
        return $this->pluginname;
    }
    
    public function getPluginModule()
    {
        return $this->modname;
    }
    
    public function getPluginTitle()
    {
        return $this->pluginname;
    }

    // to be implenented by derived classes
    public function getPluginData($lang=null, $filtAfterDate=null)
    {
        return array();
    }

    public function setPluginParameters()
    {
        return;
    }

    public function getPluginParameters()
    {
        return array ('number' => 0, 'param' => array());
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
