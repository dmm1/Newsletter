<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class Newsletter_Version extends Zikula_AbstractVersion
    {
        public function getMetaData()
        {
            $meta = array();
            $meta['displayname']    = $this->__('Newsletter');
            $meta['description']    = $this->__("Provides an configurable and automated Newsletter for your Zikula-Site.");
            $meta['url']            = $this->__('newsletter');
            $meta['version']        = '2.1.0';
			$meta['core_min'] 		= '1.3.0';
            $meta['securityschema'] = array('Newsletter::' => '::');
            return $meta;
        }
    }
