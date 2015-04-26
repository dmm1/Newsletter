<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class Newsletter_Api_Admin extends Zikula_AbstractApi
{
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'statistics')),
                             'text'  => $this->__('Start'),
                             'class' => 'z-icon-es-home');
			
			$links[] = array('url'   => ModUtil::url('Newsletter', 'user', 'main'),
                             'text'  => $this->__('Frontend'),
                             'class' => 'z-icon-es-display');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'settings'),
                             'text'  => $this->__('Settings'),
                             'class' => 'z-icon-es-config');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'message')),
                             'text'  => $this->__('Header Message'),
                             'class' => 'z-icon-es-edit');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'newsletters'),
                             'text'  => $this->__('Newsletters'),
                             'class' => 'z-icon-es-preview');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'user')),
                             'text'  => $this->__('Subscribers'),
                             'class' => 'z-icon-es-user');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'plugin')),
                             'text'  => $this->__('Plugins'),
							'class' => 'z-icon-es-cubes');

            $links[] = array('url'   => ModUtil::url('Newsletter', 'admin', 'view', array('ot'=>'userimport')),
                             'text'  => $this->__('Import / Export'),
                             'class' => 'z-icon-es-import');
        }

        return $links;
    }

    public function getLatestArchive($args)
    {
        // Get id of latest newsletter
        $where = '';
        $sort = 'id DESC';
        $objectArray = new Newsletter_DBObject_ArchiveArray();
        $data = $objectArray->get($where, $sort, 0, 1);

        return $data[0];
    }

    public function createNewsletter($args)
    {
        $Nextid  = isset($args['nextId']) ? $args['nextId'] : 0;
        $Nllanguage = isset($args['language']) ? $args['language'] : '';
        $view = Zikula_View::getInstance('Newsletter', false);

        $objArchive  = new Newsletter_DBObject_Archive();

        // Implement given newsletter Id
        if ($Nextid > 0) {
            $nextAutooincrement = $objArchive->getnextid();
            if ($Nextid != $nextAutooincrement) {
                $maxId = $objArchive->getmaxid();
                if ($Nextid > $maxId) {
                    $objArchive->setnextid($Nextid);
                } else {
                   return LogUtil::registerError($this->__('Next newsletter Id have to be grater then ').$maxId, null, ModUtil::url('Newsletter', 'admin', 'newsletters'));
                }
            }
        }

        // Language - if not set - same sequence as in html.tpl/text.tpl
        if (empty($Nllanguage)) {
            $Nllanguage = System::getVar('language_i18n', 'en');
        }
        ZLanguage::setLocale($Nllanguage);

        // Get newsletter content
        $nlDataObjectArray = new Newsletter_DBObject_NewsletterDataArray();
        $objNewsletterData  = $nlDataObjectArray->getNewsletterData($Nllanguage);
        $view->assign('show_header', '1');
        $view->assign('site_url', System::getBaseUrl());
        $view->assign('site_name', System::getVar('sitename'));
        $view->assign('objectArray', $objNewsletterData);
        $message_html = $view->fetch('output/'.$this->getVar('template_html', 'html.tpl'));
        $message_text = $view->fetch('output/text.tpl');

        // Prepare data
        $archiveData = array();
        $archiveData['date'] = DateUtil::getDatetime();
        //$archiveData['lang'] = $this->_objLang;
        $archiveData['lang'] = $Nllanguage;
        $archiveData['n_plugins'] = $objNewsletterData['nPlugins'];
        $archiveData['n_items'] = $objNewsletterData['nItems'];
        $archiveData['text'] = $message_text;
        $archiveData['html'] = $message_html;

        // Create new archive
        $objArchive->setData($archiveData);
        $result = $objArchive->save($archiveData);
        if ($result) {
            return $result['id'];
        } else {
            return LogUtil::registerError(($this->__('Error creating newsletter in archive.')));
        }
    }

    public function sendNewsletter($args)
    {
        if (isset($args['id'])) {
            $id = $args['id'];
        } else {
            $archive = $this->getLatestArchive(array());
            $id = $archive['id'];
        }
        $send_per_batch = isset($args['send_per_batch']) ? $args['send_per_batch'] : 0;

        // Get last archive
        $objArchive  = new Newsletter_DBObject_Archive();
        $dataNewsletter = $objArchive->get($id);
        if ($dataNewsletter) {
            //Set language
            $lang = $dataNewsletter['lang'] ? $dataNewsletter['lang'] : System::getVar('language_i18n', 'en');
            $enable_multilingual = $this->getVar('enable_multilingual', 0);

            // Determine users to send to
            $where = "(nlu_active=1 AND nlu_approved=1)";
            if ($enable_multilingual) {
                $where = "(nlu_lang='".$lang."' OR nlu_lang='')";

                //Set language
                ZLanguage::setLocale($lang);
            }
            // not take in account frequency in manual sending
            //$allow_frequency_change = ModUtil::getVar ('Newsletter', 'allow_frequency_change', 0);
            //$default_frequency = ModUtil::getVar ('Newsletter', 'default_frequency', 1);
            $objectUserArray = new Newsletter_DBObject_UserArray();
            $users = $objectUserArray->get($where, 'id');
            // Create send object
            $objSend = new Newsletter_DBObject_NewsletterSend();
            $objSend->_objUpdateSendDate = true;

            // Scan users
            if ($objSend->_setStartexecution()) {
                $alreadysent = 0;
                $nowsent = 0;
                $notsent = 0;
                $newSentTime = DateUtil::getDatetime();
                foreach ($users as $user) {
                    if  ($user['last_send_nlid'] == $id) {
                        $alreadysent++;
                    } else {
                        // Send to subscriber
                        $user['last_send_nlid'] = $id;
                        if ($user['type'] == 1) {
                            $html = false;
                            $message = $dataNewsletter['text'];
                        } else {
                            $html = true;
                            $message = $dataNewsletter['html'];
                        }
                        if ($objSend->_sendNewsletter($user, $message, $html)) {
                            $nowsent++;
                        } else {
                            $notsent++;
                        }
                    }
                    if ($send_per_batch > 0 && $nowsent >= $send_per_batch) {
                        LogUtil::registerStatus($this->__f('Reached max emails to send in batch: %s', $send_per_batch));
                        break;
                    }
                }

                $objSend->_setEndexecution($nowsent);

                LogUtil::registerStatus($this->__f('Newsletter successfully send to subscribers: %s', $nowsent));
                if ($alreadysent) {
                    LogUtil::registerStatus($this->__f('Skipped (already sent): %s', $alreadysent));
                }
                if ($notsent) {
                    LogUtil::registerStatus($this->__f('Skipped (not sent for some reason): %s', $notsent));
                }
            } else {
                LogUtil::registerError($this->__f('Max emails per hour encountered: %s', $this->getVar('max_send_per_hour')));
            }
        } else {
            LogUtil::registerError($this->__f('Error getting data for newsletter Id %s', $id));
        }

        return true;
    }
}
