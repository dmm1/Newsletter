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


function Newsletter_user_main ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_OVERVIEW);
    } 
    $ot         = FormUtil::getPassedValue ('ot', 'main', 'GETPOST');
    $offset     = FormUtil::getPassedValue ('startnum', 0, 'GETPOST');
    $pagesize   = FormUtil::getPassedValue ('pagesize', pnModGetVar ('Newsletter', 'itemsperpage', 30), 'GETPOST');
    $startpage  = isset($args['startpage']) ? 1 : 0;
    $sort       = null;

    $pnRender = pnRender::getInstance('Newsletter', false);
    $pnRender->assign ('ot', $ot);
    $pnRender->assign ('startpage', $startpage);

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    $data = array();
    if (($ot && $class = Loader::loadArrayClassFromModule ('Newsletter', $ot))) {
        $objectArray = new $class ();
        $where       = $objectArray->genFilter ();
        $sort        = $sort ? $sort : $objectArray->_objSort;
        $data        = $objectArray->get ($where, $sort, $offset, $pagesize);

        $pager = array();
        $pager['numitems']     = $objectArray->getCount ($where);
        $pager['itemsperpage'] = $pagesize;
        $pnRender->assign ('startnum', $offset);
        $pnRender->assign ('pager', $pager);
    } //elseif ($debug) {
    //    return "Unable to load array class [$ot]";
    //} 
    $pnRender->assign ('objectArray', $data);

    if (Loader::loadClassFromModule ('Newsletter', 'user')) {
        $object = new PNUser ();
        if (pnUserLoggedIn()) {
            $user = $object->getUser (pnUserGetVar('uid'));
	}
        $validation = $object->getValidation();
    }
    $pnRender->assign ('user', $user);

    $tpl = 'newsletter_user_view_' . $ot . '.html';
    return $pnRender->fetch($tpl);
}


function Newsletter_user_detail () // hardcoded for archives
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_OVERVIEW);
    } 

    $ot  = 'archive';
    $id  = (int)FormUtil::getPassedValue ('id', 0);
    $url = pnModURL('Newsletter', 'user', 'main');

    if (!$id) {
        return LogUtil::registerError ('Invalid [id] parameter received', null, $url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError ("Unable to load class [$ot]", null, $url);
    }

    $obj  = new PNArchive ();
    $data = $obj->get ($id);

    // just echo text and exit; no need to use template
    print $data['text'];
    exit ();
}


function Newsletter_user_send ()
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_PN_TEXT_NOAUTH_READ);
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_send')) {
        return 'Unable to load class [newsletter_send]';
    }

    $scheduled = (int)FormUtil::getPassedValue ('scheduled', 0);

    $obj = new PNNewsletterSend ();
    return $obj->save (array('respond' => 1, 'scheduled' => $scheduled));
}

