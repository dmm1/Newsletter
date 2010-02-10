<?php

/**
 * Newletter Module for Zikula
 *
 * @copyright � 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

function Newsletter_ajax_getusers()
{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return true;
    }

    $fragment = FormUtil::getpassedValue('fragment');

    pnModDBInfoLoad('Newsletter');
    $pntable = pnDBGetTables();

    $userscolumn = $pntable['newsletter_users_column'];

    $where = 'WHERE ' . $userscolumn['name'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\'';
    $results = DBUtil::selectObjectArray('newsletter_users', $where);

    $out = '<ul>';
    if (is_array($results) && count($results) > 0) {
        foreach($results as $result) {
            $out .= '<li>' . DataUtil::formatForDisplay($result['name']) .'<input type="hidden" id="' . DataUtil::formatForDisplay($result['uname']) . '" value="' . $result['uid'] . '" /></li>';
        }
    }
    $out .= '</ul>';
    echo DataUtil::convertToUTF8($out);
    return true;
}


