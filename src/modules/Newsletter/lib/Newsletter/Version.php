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
        $meta['version']        = '2.1.0';
        $meta['core_min']       = '1.3.0';
        $meta['securityschema'] = array('Newsletter::' => '::');
        return $meta;
    }
}
