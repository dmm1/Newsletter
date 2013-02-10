<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage Ajax
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
 
class Newsletter_Controller_Ajax extends Zikula_Controller_AbstractAjax
{
    public function getusers()
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN));

        $fragment = FormUtil::getPassedValue('fragment');

        ModUtil::dbInfoLoad('Newsletter');
        $tables = DBUtil::getTables();

        $userscolumn = $tables['newsletter_users_column'];

        $where = 'WHERE ' . $userscolumn['name'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\'';
        $results = DBUtil::selectObjectArray('newsletter_users', $where);

        $out = '<ul>';
        if (is_array($results) && count($results) > 0) {
            foreach($results as $result) {
                $out .= '<li>' . DataUtil::formatForDisplay($result['name']) .'<input type="hidden" id="' . DataUtil::formatForDisplay($result['uname']) . '" value="' . $result['uid'] . '" /></li>';
            }
        }
        $out .= '</ul>';

        return new Zikula_Response_Ajax_Plain($out);
    }
}
