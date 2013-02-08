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
        ModUtil::dbInfoLoad('Newsletter');

        $user = new Newsletter_DBObject_User();
        $user->select((int)$userObj['uid'], null, 'uid');
        $user->_objField = 'id';

        if($user->_objData != null) {
            //User is a Newsletter subscriber
            //We don't have to change the email adress to the new one. That is done in Newsletter_DBObject_User::updatePreProcess() automatically
            $user->update();
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
        $userObj = $event->getSubject();
        ModUtil::dbInfoLoad('Newsletter');

        $user = new Newsletter_DBObject_User();
        $user->select((int)$userObj['uid'], null, 'uid');
        $user->_objField = 'id';

        if($user->_objData != null) {
            //User is a Newsletter subscriber
            $where = $user->genWhere((int)$userObj['uid'], null, null);

            DBUtil::deleteWhere($user->_objType, $where);
        }

    }
}
