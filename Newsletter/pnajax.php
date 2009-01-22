<?php
/**
 * Zikula Application Framework
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 
/**
 * getusers
 * performs a user search based on the fragment entered so far
 * @author Frank Schummertz
 * @modified by Dominik Mayer for Newsletter Module
 * @param fragment string the fragment of the username entered
 * @return void nothing, direct ouptut using echo!
 */
function Newsletter_ajax_getusers()
{
    $fragment = FormUtil::getpassedValue('fragment');

    pnModDBInfoLoad('Newsletter');
    $pntable = pnDBGetTables();

    $userscolumn = $pntable['newsletter_users'];

    $where = 'WHERE ' . $userscolumn['username'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\'';
    $results = DBUtil::selectObjectArray('users', $where);

    $out = '<ul>';
    if (is_array($results) && count($results) > 0) {
        foreach($results as $result) {
            $out .= '<li>' . DataUtil::formatForDisplay($result['username']) .'<input type="hidden" id="' . DataUtil::formatForDisplay($result['username']) . '" value="' . $result['username'] . '" /></li>';
        }
    }
    $out .= '</ul>';
    echo DataUtil::convertToUTF8($out);
    return true;
}
