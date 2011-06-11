<?php

/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class PNPluginMediashareArray extends PNPluginBaseArray 
{
    function PNPluginMediashareArray($init = null, $where = '') 
    {
        $this->PNPluginBaseArray();
    }


    function getPluginData($lang = null) 
    {
        if (!ModUtil::available('Mediashare')) {
            return array ();
        }

        $nItems = ModUtil::getVar('Newsletter', 'plugin_Mediashare_nItems', 1);
        $mediashareAlbumsOnly = ModUtil::getVar('Newsletter', 'mediashareAlbumsOnly', 0);
        $mediasharePath = ModUtil::getVar('Newsletter', 'mediasharePath', 1);
        if ($mediashareAlbumsOnly == 1) {
            return ModUtil::apiFunc('Mediashare', 'user', 'getAlbumList', 
                                 array ( 'order'     => 'id',
                                         'orderDir'  => 'DESC',
                                         'recordPos' => 0,
                                         'pageSize'  => $nItems )
            );
        } else {
            return ModUtil::apiFunc('Mediashare', 'user', 'getLatestMediaItems', 
                                 array ( 'order'     => 'id',
                                         'orderDir'  => 'DESC',
                                         'recordPos' => 0,
                                         'pageSize'  => $nItems )
            );
        }
    }


    function setPluginParameters() 
    {
        $albumonly = FormUtil :: getPassedValue('mediashareAlbumsOnly', 0, 'POST');
        ModUtil::setVar('Newsletter', 'mediashareAlbumsOnly', $albumonly);
        $path = FormUtil :: getPassedValue('mediasharePath', 1, 'POST');
        ModUtil::setVar('Newsletter', 'mediasharePath', $path);
    }


    function getPluginParameters() 
    {
        $mediashareAlbumsOnly = ModUtil::getVar('Newsletter', 'mediashareAlbumsOnly', 0);
        $mediasharePath = ModUtil::getVar('Newsletter', 'mediasharePath', 1);
        return array ( 'number'               => 1,
                       'param'                => array ('mediashareAlbumsOnly' => $mediashareAlbumsOnly,
                                                         'mediasharePath'      => $mediasharePath)
        );
    }
}
