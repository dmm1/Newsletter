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

class NewsletterUtil 
{
    function encodeText ($string)
    {
        $search  = array ('Ä', 'Ć', 'Č', 'Đ', 'Ö', 'Š', 'Ü', 'Ž', 'ä', 'ć', 'č', 'đ', 'ö', 'š', 'ü', 'ž', 'ß');
        $replace = array ('&Auml;', '&#262;', '&#268;', '&#272;', '&Ouml;', '&#352;', '&Uuml;', '&#381;', '&auml;', '&#263;', '&#269;', '&#273;', '&ouml;', '&#353;', '&uuml;', '&#382;', '&szlig;');

        return str_replace ($search, $replace, $string);
    }


    function getActivePlugins ()
    {
        $plugins = NewsletterUtil::getPluginClasses ();
        foreach ($plugins as $k=>$plugin) {
            $modvarName = 'plugin_' . $plugin;
            $active = pnModGetVar ('Newsletter', $modvarName, 0);
            if (!$active) {
                unset ($plugins[$k]);
            }
        }

        return $plugins;
    }


    function getPluginClasses ()
    {
        $ignoreFiles = array();
        $ignoreFiles[] = 'PNPlugin.class.php';
        $ignoreFiles[] = 'PNPluginArray.class.php';
        $ignoreFiles[] = 'PNPluginBaseArray.class.php';

        $files = NewsletterUtil::scandir ('modules/Newsletter/classes', $ignoreFiles, 'Plugin');

        // get plugin module base names
        $plugins = array();
        foreach ($files as $file) {
            if (strpos($file, '.') === 0) {
                continue;
            }
            $t = str_replace ('PNPlugin', '', $file);
            $t = str_replace ('Array.class.php', '', $t);
            $plugins[$file] = $t;
        }

        return $plugins;
    }


    function getSelectorDataActive ($all=true)
    {
        pnModLangLoad ('Newsletter', 'common');

        $array = array();
        if ($all) {
          $array[-1] = pnML('_ALL');
        }
        $array[0] = _NEWSLETTER_INACTIVE;
        $array[1] = _NEWSLETTER_ACTIVE;

        return $array;
    }


    function getSelectorDataApproved ($all=true)
    {
        pnModLangLoad ('Newsletter', 'common');

        $array = array();
        if ($all) {
          $array[-1] = pnML('_ALL');
        }
        $array[0] = _NEWSLETTER_NOT_APPROVED;
        $array[1] = _NEWSLETTER_APPROVED;

        return $array;
    }


    function getSelectorDataArchiveExpire ()
    {
        pnModLangLoad ('Newsletter', 'common');

        $array = array();
        $array[0] = _NEWSLETTER_NEVER;
        $array[1] = _NEWSLETTER_1MONTH;
        $array[2] = _NEWSLETTER_2MONTHS;
        $array[3] = _NEWSLETTER_3MONTHS;
        $array[6] = _NEWSLETTER_6MONTHS;
        $array[12] = _NEWSLETTER_1YEAR;

        return $array;
    }


    function getSelectorDataLanguage ($all=false)
    {
        $languages = pnInstalledLanguages();
        if ($all) {
            $languages = array_merge (array(''=>_ALL), $languages);
        }

        return $languages;
    }


    function getSelectorDataNewsletterFrequency ($all=false)
    {
        pnModLangLoad ('Newsletter', 'common');

        $array = array();
        if ($all) {
          $array[-1] = pnML('_ALL');
        }
        $array[0]  = _NEWSLETTER_FREQUENCY_WEEKLY;
        $array[1]  = _NEWSLETTER_FREQUENCY_MONTHLY;
        $array[2]  = _NEWSLETTER_FREQUENCY_2MONTHS;
        $array[3]  = _NEWSLETTER_FREQUENCY_3MONTHS;
        $array[6]  = _NEWSLETTER_FREQUENCY_6MONTHS;
        $array[9]  = _NEWSLETTER_FREQUENCY_9MONTHS;
        $array[12] = _NEWSLETTER_FREQUENCY_YEARLY;

        return $array;
    }


    function getSelectorDataNewsletterType ($all=false)
    {
        pnModLangLoad ('Newsletter', 'common');

        $array = array();
        if ($all) {
          $array[0] = pnML('_ALL');
        }
        $array[1] = _NEWSLETTER_FORMAT_TEXT;
        $array[2] = _NEWSLETTER_FORMAT_HTML;
        $array[3] = _NEWSLETTER_FORMAT_TEXTWLINK;

        return $array;
    }


    function getSelectorDataSendDay ()
    {
        pnModLangLoad ('Newsletter', 'common');
        
        return array('1' => pnML('_NEWSLETTER_DAY_MONDAY'),
                     '2' => pnML('_NEWSLETTER_DAY_TUESDAY'), 
                     '3' => pnML('_NEWSLETTER_DAY_WEDNESDAY'),
                     '4' => pnML('_NEWSLETTER_DAY_THURSDAY'), 
                     '5' => pnML('_NEWSLETTER_DAY_FRIDAY'), 
                     '6' => pnML('_NEWSLETTER_DAY_SATURDAY'), 
                     '0' => pnML('_NEWSLETTER_DAY_SUNDAY'));
    }


    function scandir ($directory, $ignoreFiles=null, $matchString=null)
    {
        $files = array();
        if (!$directory) {
            LogUtil::registerError ("Invalid [directory] received");
            return $files;
        }

        if (!file_exists($directory)) {
            LogUtil::registerError ("Directory [$directory] does not seem to exist");
            return $files;
        }

        if (is_file($directory)) {
            LogUtil::registerError ("Directory [$directory] seems to be a file and not a directory");
            return $files;
        }

        // PHP 4/5 compat check
        if (function_exists('scandir')) {
            $files = scandir($directory);
        } else {
            $dir = opendir($directory);
            while ($fName = readdir($dir)) {
                $files[] = $fName;
            }
        }

        if ($ignoreFiles) {
            foreach ($files as $k=>$v) {
                if (in_array($v, $ignoreFiles))  {
                    unset ($files[$k]);
                }
            }
        }

        if ($matchString) {
            foreach ($files as $k=>$v) {
                if (strpos($v, $matchString) === false) {
                    unset ($files[$k]);
                }
            }
        }

        return $files;
    }
}


// compat method with function not in Zikula core yet
if (!function_exists('pnInstalledLanguages')) {
    function pnInstalledLanguages ()
    {
        $lang = languagelist();
        $handle = opendir('language');
        while (false !== ($f = readdir($handle))) {
            if (is_dir("language/$f") && isset($lang[$f])) {
                $langlist[$f] = $lang[$f];
            }
        }
        closedir ($handle);

        asort($langlist);
        return $langlist;
    }
}

