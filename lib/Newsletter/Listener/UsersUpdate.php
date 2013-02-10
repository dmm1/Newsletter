<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage Listener
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

/**
 * Provides listeners (handlers) for Users module: user update and delete events.
 */
class Newsletter_Listener_UsersUpdate
{
    /**
     * Updates the email adress saved in the Newsletter database, if a user changes his adress.
     *
     * @param Zikula_Event $event The event that triggered this handler.
     *
     * @return void
     */
    public static function updateAccountListener(Zikula_Event $event)
    {
        $userObj = $event->getSubject();
        $args    = $event->getArgs();

        //Filter UserUtil::setVar calls, which aren't change the email adress
        if($args['action'] == 'setVar' && $args['field'] != 'email')
            return;

        // Load module, otherwise translation is not working
        ModUtil::load('Newsletter');
        $dom = ZLanguage::getModuleDomain('Newsletter');

        ModUtil::dbInfoLoad('Newsletter');
        $tables = DBUtil::getTables();
        $column   = $tables['newsletter_users_column'];
        $where = "WHERE $column[uid]='" . $userObj['uid'] . "'";
        $user = DBUtil::selectObject('newsletter_users', $where);
        if(!empty($user)) {
            //User is a Newsletter subscriber
            if ($user['email'] != $userObj['email']) {
                // User email is changed, let's change in newsletter_users
                $user = array('email' => $userObj['email']);

                if(DBUtil::updateObject($user, 'newsletter_users', $where)) {
                    LogUtil::registerStatus(__('Email adress for newsletter subscribtion changed.', $dom));
                } else {
                    LogUtil::registerStatus(__('Email adress for newsletter subscribtion NOT changed.', $dom));
                }
            }
        }
    }
    
    /**
     * Deletes a user out of the Newsletter database
     *
     * @param Zikula_Event $event The event that triggered this handler.
     *
     * @return void
     */
    public static function deleteAccountListener(Zikula_Event $event)
    {
        // Load module, otherwise translation is not working
        ModUtil::load('Newsletter');
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $userObj = $event->getSubject();
        ModUtil::dbInfoLoad('Newsletter');

        $user = new Newsletter_DBObject_User();
        $user->select((int)$userObj['uid'], null, 'uid');
        $user->_objField = 'id';

        if($user->_objData != null) {
            //User is a Newsletter subscriber
            $where = $user->genWhere((int)$userObj['uid'], null, null);

            DBUtil::deleteWhere($user->_objType, $where);
            LogUtil::registerStatus(__('Newsletter subscribtion canceled.', $dom));
        }
    }
}
