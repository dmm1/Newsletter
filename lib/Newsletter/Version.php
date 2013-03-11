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

class Newsletter_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Newsletter');
        $meta['description']    = $this->__('Provides a configurable and automated Newsletter for your Zikula site.');
        $meta['url']            = $this->__('newsletter');
        $meta['version']        = '2.2.5';
        $meta['core_min']       = '1.3.0';
        $meta['core_max']       = '1.3.99';
        $meta['capabilities']   = array(HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true),
                                        HookUtil::PROVIDER_CAPABLE   => array('enabled' => true));
        $meta['securityschema'] = array('Newsletter::' => '::');
        return $meta;
    }

    protected function setupHookBundles()
    {
        // Register hooks
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.newsletter.ui_hooks.items', 'ui_hooks', $this->__('Newsletter Hooks'));
        $bundle->addEvent('form_edit', 'newsletter.ui_hooks.items.form_edit');
        $this->registerHookSubscriberBundle($bundle);

        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.newsletter.ui_hooks.subscribe', 'ui_hooks', $this->__('Subscribe to Newsletter'));
        $bundle->addServiceHandler('form_edit', 'Newsletter_HookHandlers', 'uiEdit', 'newsletter.subscribe');
        $bundle->addServiceHandler('process_edit', 'Newsletter_HookHandlers', 'processEdit', 'newsletter.subscribe');
        $this->registerHookProviderBundle($bundle);
    }
}
