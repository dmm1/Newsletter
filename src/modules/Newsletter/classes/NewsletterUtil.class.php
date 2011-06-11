<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2010, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class NewsletterUtil 
{
    function encodeText ($string)
    {
        $search  = array ('Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'À', 'Á', 'Â', 'Ã', 'Å', 'Æ' );
        $replace = array ('&Auml;', '&auml;', '&Ouml;', '&ouml;', '&Uuml;', '&uuml;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Aring;', '&AElig;'  );

        return str_replace ($search, $replace, $string);
    }


    function getActivePlugins ()
    {
        $plugins = NewsletterUtil::getPluginClasses ();
        foreach ($plugins as $k=>$plugin) {
            $modvarName = 'plugin_' . $plugin;
            $active = ModUtil::getVar ('Newsletter', $modvarName, 0);
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
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $array = array();
        if ($all) {
          $array[-1] = __('All', $dom);
        }
        $array[0] = __('Inactive', $dom);
        $array[1] = __('Active', $dom);

        return $array;
    }


    function getSelectorDataApproved ($all=true)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $array = array();
        if ($all) {
          $array[-1] = __('All', $dom);
        }
        $array[0] = __('Not Approved', $dom);
        $array[1] = __('Approved', $dom);

        return $array;
    }


    function getSelectorDataArchiveExpire ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $array = array();
        $array[0] = __('Never', $dom);
        $array[1] = __('1 Month', $dom);
        $array[2] = __('2 Months', $dom);
        $array[3] = __('3 Months', $dom);
        $array[6] = __('6 Months', $dom);
        $array[12] = __('1 Year', $dom);

        return $array;
    }


    function getSelectorDataLanguage ($all=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $languages = Compat_LanguageUtil_getLanguages();
        if ($all) {
            $languages = array_merge (array(''=>__('All', $dom)), $languages);
        }

        return $languages;
    }


    function getSelectorDataNewsletterFrequency ($all=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $array = array();
        if ($all) {
          $array[-1] = __('All', $dom);
        }
        $array[0]  = __('Weekly', $dom);
        $array[1]  = __('Monthly', $dom);
        $array[2]  = __('Every 2 Months', $dom);
        $array[3]  = __('Every 3 Months', $dom);
        $array[6]  = __('Every 6 Months', $dom);
        $array[9]  = __('Every 9 Months', $dom);
        $array[12] = __('Yearly', $dom);

        return $array;
    }


    function getSelectorDataNewsletterType ($all=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $array = array();
        if ($all) {
          $array[0] = __('All', $dom);
        }
        $array[1] = __('Text', $dom);
        $array[2] = __('HTML', $dom);
        $array[3] = __('Text with Link to Archive', $dom);

        return $array;
    }


    function getSelectorDataSendDay ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        
        return array('1' => __('Monday', $dom),
                     '2' => __('Tuesday', $dom),
                     '3' => __('Wednesday', $dom),
                     '4' => __('Thursday', $dom),
                     '5' => __('Firday', $dom),
                     '6' => __('Saturday', $dom),
                     '0' => __('Sunday', $dom));
    }


    function scandir ($directory, $ignoreFiles=null, $matchString=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');
        $files = array();
        if (!$directory) {
            LogUtil::registerError (__("Invalid [directory] received", $dom));
            return $files;
        }

        if (!file_exists($directory)) {
            LogUtil::registerError (__("Directory [$directory] does not seem to exist", $dom));
            return $files;
        }

        if (is_file($directory)) {
            LogUtil::registerError (__("Directory [$directory] seems to be a file and not a directory", $dom));
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

