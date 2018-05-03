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

class Newsletter_Block_Signup extends Zikula_Controller_AbstractBlock
{
    public function init()
    {
        SecurityUtil::registerPermissionSchema('Signupblock::', 'Block ID::');
    }

    public function info()
    {
        return array(
            'module'          => 'Newsletter',
            'text_type'       => $this->__('Signup'),
            'text_type_long'  => $this->__('Display a newsletter signup form'),
            'allow_multiple'  => true,
            'form_content'    => false,
            'form_refresh'    => false,
            'show_preview'    => true,
            'admin_tableless' => true
        );
    }

    public function display($blockinfo)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Signupblock::', "$blockinfo[bid]::", ACCESS_READ));

        if (!ModUtil::available('Newsletter') || !ModUtil::loadApi('Newsletter', 'user')) {
            return;
        }

        $loggedin = UserUtil::isLoggedIn();
        $allow_anon = ModUtil::getVar('Newsletter','allow_anon_registration');

        if (!$allow_anon && !$loggedin) {
            return;
        }

        if ($loggedin) {
            $class = 'Newsletter_DBObject_User';
            if (class_exists($class)) {
                $object = new Newsletter_DBObject_User();
                $data   = $object->getUser(UserUtil::getVar('uid'));
                if ($data) {
                    return;
                }
            } else {
                return;
            }
        }

        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $this->view->assign('require_tos', $vars['require_tos'])
                   ->assign('nl_frequency', $vars['nl_frequency'])
                   ->assign('nl_type', $vars['nl_type']);

        $blockinfo['content'] = $this->view->fetch('block/signup.tpl');

        return BlockUtil::themeBlock($blockinfo);
    }

    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $this->view->setCaching(Zikula_View::CACHE_DISABLED);

        $this->view->assign('require_tos', $vars['require_tos']);
        $this->view->assign('nl_frequency_sel', isset($vars['nl_frequency']) ? $vars['nl_frequency'] : $this->getVar('default_frequency'));
        $this->view->assign('nl_type_sel', isset($vars['nl_type']) ? $vars['nl_type'] : $this->getVar('default_type'));

        // return the output that has been generated by this function
        return $this->view->fetch('block/signup_modify.tpl');
    }

    public function update($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $vars['nl_frequency'] = (int)FormUtil::getPassedValue('nl_frequency', 1, 'POST');
        $vars['require_tos']  = (int)FormUtil::getPassedValue('require_tos', 1, 'POST');
        $vars['nl_type']      = (int)FormUtil::getPassedValue('nl_type', 1, 'POST');

        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        $this->view->clear_cache('block/signup.tpl');

        return $blockinfo;
    }
}
