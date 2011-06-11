<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 * Security fix: thx @mumuri from community.zikula.org 
 */
 class PNPluginDizkusArray extends PNPluginBaseArray
{
    function PNPluginDizkusArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }

    function getPluginData ($lang=null)
    {
        if (!pnModAvailable('Dizkus')) {
            return array();
        }

        pnModDBInfoLoad ('Dizkus');
        $nItems = pnModGetVar ('Newsletter', 'plugin_Dizkus_nItems', 1);

        // get all forums the user is allowed to read
        $userforums = pnModAPIFunc('Dizkus', 'user', 'readuserforums');
        if(!is_array($userforums) || count($userforums)==0) {
            // error or user is not allowed to read any forum at all
            // return empty result set without even doing a db access
            return array($posts, $m2fposts, $rssposts, $text);
        }

        // now create a very simple array of forum_ids only. we do not need
        // all the other stuff in the $userforums array entries
        $allowedforums = array();
        for($i=0; $i<count($userforums); $i++) {
            array_push($allowedforums, $userforums[$i]['forum_id']);
        }
        $whereforum = ' forum_id IN (' . pnVarPrepForStore(implode($allowedforums, ',')) . ') ';
        return DBUtil::selectObjectArray('dizkus_topics', $whereforum, 'topic_id DESC', 0, $nItems);
    }
}

