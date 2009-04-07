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


function Newsletter_admin_main() 
{
   return Newsletter_admin_modifyconfig ();
}


function Newsletter_admin_modifyconfig () 
{
    if (!SecurityUtil::checkPermission('Newsletter::modifyconfig', '::', ACCESS_ADMIN)) {
        return _PN_TEXT_NOAUTH_ADMIN;
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    $preferences = pnModGetVar('Newsletter');
    $pnRender = pnRender::getInstance('Newsletter', false);
    $pnRender->assign ('preferences', $preferences);
    $pnRender->assign ('last_execution_time', pnModGetVar('Newsletter','end_execution_time') - pnModGetVar('Newsletter','start_execution_time'));
    $pnRender->assign ('last_execution_count', pnModGetVar('Newsletter','end_execution_count', 0));

    return $pnRender->fetch('newsletter_admin_form_modifyconfig.html');
}


function Newsletter_admin_edit () 
{
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError (_PN_TEXT_NOAUTH_ADMIN, null, $url);
    }

    $ot  = 'user';
    $id  = (int)FormUtil::getPassedValue ('id', 0, 'GETPOST');
    $url = pnModURL('Newsletter', 'admin', 'main');

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError ('Unable to load class [newsletter_util]', null, $url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError ("Unable to load class for [$ot]", null, $url);
    }

    $object = new $class ();
    if ($id) {
        $data = $object->get ($id);
        if (!$data) {
            $url = pnModURL('Newsletter', 'admin', 'view', array('ot' => $ot));
            return LogUtil::registerError ("Unable to retrieve object of type [$ot] with id [$id]", null, $url);
        } 
    } else {
        $data = array();
    }

    $pnRender = pnRender::getInstance('Newsletter', false);
    $pnRender->assign ('ot', $ot);
    $pnRender->assign ($ot, $data);
    $pnRender->assign ('validation', $object->getValidation());

    $tpl = 'newsletter_admin_form_' . $ot . '.html';
    return $pnRender->fetch($tpl);
}


function Newsletter_admin_view () 
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerError (_PN_TEXT_NOAUTH_ADMIN, null, $url);
    }

    $dPagesize = pnModGetVar ('Newsletter', 'pagesize', 25);
    $filter    = FormUtil::getPassedValue ('filter', array(), 'GETPOST');
    $format    = FormUtil::getPassedValue ('format', null, 'GETPOST');
    $ot        = FormUtil::getPassedValue ('ot', null, 'GETPOST');
    $otTarget  = FormUtil::getPassedValue ('otTarget', null, 'GETPOST');
    $offset    = FormUtil::getPassedValue ('startnum', 0, 'GETPOST');
    $sort      = FormUtil::getPassedValue ('sort', null, 'GETPOST');
    $url       = pnModURL('Newsletter', 'user', 'view', array('ot' => $ot));
    $pagesize  = FormUtil::getPassedValue ('pagesize', SessionUtil::getVar('pagesize', $dPagesize, '/Newsletter'));

    if ($sort) {
        $filter['sort'] = $sort;
    }

    if (isset($filter['sort']) && $filter['sort']) {
        // reverse sort order if we sort on the same field again
        $sort    = $filter['sort'];
        $oldSort = SessionUtil::getVar ('oldSort', $sort, '/Newsletter');
        $oldOt   = SessionUtil::getVar ('oldOt', $sort, '/Newsletter');
        if ($ot == $oldOt && $sort == $oldSort && strpos ($sort, 'DESC')===false) {
            $sort .= ' DESC';
        }
    }
    if (!$sort) {
        'cr_date DESC';
    }
    SessionUtil::setVar ('oldSort', $sort, '/Newsletter');
    SessionUtil::setVar ('oldOt', $ot, '/Newsletter');
    SessionUtil::setVar ('pagesize', $pagesize, '/Newsletter');

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError ('Unable to load class [newsletter_util]', null, $url);
    }

    $pnRender = pnRender::getInstance('Newsletter', false);
    $pnRender->assign ('ot', $ot);

    $data  = array();
    if ($ot) {
        if (($class = Loader::loadArrayClassFromModule ('Newsletter', $ot))) {
          $objectArray = new $class ();
          if (method_exists($objectArray, 'cleanFilter')) {
              $filter = $objectArray->cleanFilter($filter);
          }
          $where = $objectArray->genFilter ($filter);
          $sort  = $sort ? $sort : $objectArray->_objSort;
          $data  = $objectArray->get ($where, $sort, $offset, $pagesize);

          $pager = array();
          $pager['numitems']     = $objectArray->getCount ($where, true);
          $pager['itemsperpage'] = $pagesize;
          $pnRender->assign ('startnum', $offset);
          $pnRender->assign ('pager', $pager);
          $pnRender->assign ('startpage', false);
       }
    }

    $pnRender->assign ('objectArray', $data);

    if ($ot == 'user') {
        $pnRender->assign ('filter', $filter);
    }

    if ($ot == 'show_preview') {
        switch ($format){
            case 1:
                $content = $pnRender->fetch('newsletter_template_text.html');
                $content = str_replace(array("\n","\r"),'<br />',$content);
                break;
            case 2:
                $content = $pnRender->fetch('newsletter_template_html.html');
                break;
            case 3:
                $content = $pnRender->fetch('newsletter_template_text_with_link.html');
                $content = str_replace(array("\n","\r"),'<br />',$content);
                break;
            default: 
                $content = "Invalid format [$format] specified ...";
        }
        $testsend = FormUtil::getPassedValue ('testsend', 0, 'POST');
        $testsendEmail = FormUtil::getPassedValue ('testsend_email', 0, 'POST');
        if ($testsend) {
            $rc = true;
            if (!$testsendEmail) {
                $rc = LogUtil::registerError (_NEWSLETTER_EMAIL_EMPTY);
            }
            if (!pnVarValidate($testsendEmail, 'email')) {
                $rc = LogUtil::registerError (_NEWSLETTER_EMAIL_INVALID);
            }
            if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_send')) {
                $rc = LogUtil::registerError ('Unable to load class [newsletter_send]', null, $url);
            } 
            
            if ($rc) {
                $sendObj = new PNNewsletterSend ();
                if ($sendObj->save ()) {
                    LogUtil::registerStatus (_NEWSLETTER_EMAIL_SUCCESS);
                } else {
                    LogUtil::registerError (_NEWSLETTER_EMAIL_FAILURE);
                }
           }
        }
        echo $content;
        exit;
    } 

    $template = 'newsletter_admin_view_' . $ot . '.html';
    return $pnRender->fetch($template);
}

