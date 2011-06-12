<?php

/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */
 
class Newsletter_Controller_Ajax extends Zikula_Controller_AbstractAjax
{
	public functiongetusers()
	{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return true;
    }

    $fragment = FormUtil::getpassedValue('fragment');

    ModUtil::dbInfoLoad('Newsletter');
    $pntable = DBUtil::getTables();

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
}