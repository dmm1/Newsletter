<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage Util
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class Newsletter_Util
{
    // FIXME this is really needed with UTF-8 database?
    function encodeText ($string)
    {
        $replace = array(
            'Ä' => '&Auml;',
            'ä' => '&auml;',
            'Ö' => '&Ouml;',
            'ö' => '&ouml;',
            'Ü' => '&Uuml;',
            'ü' => '&uuml;',
            'À' => '&Agrave;',
            'Á' => '&Aacute;',
            'Â' => '&Acirc;',
            'Ã' => '&Atilde;',
            'Å' => '&Aring;',
            'Æ' => '&AElig;'
        );

        return str_replace(array_keys($replace), array_values($replace), $string);
    }

    function getActivePlugins ()
    {
        $plugins = self::getPluginClasses();

        foreach ($plugins as $k=>$plugin) {
            $modvarName = 'plugin_' . $plugin;
            $active = ModUtil::getVar('Newsletter', $modvarName, false);
            if (!$active) {
                unset($plugins[$k]);
            }
        }

        return $plugins;
    }

    function getPluginClasses()
    {
        $ignoreFiles = array();
        $ignoreFiles[] = 'PNPlugin.class.php';
        $ignoreFiles[] = 'PNPluginArray.class.php';
        $ignoreFiles[] = 'PNPluginBaseArray.class.php';

        $files = self::scandir('modules/Newsletter/classes', $ignoreFiles, 'Plugin');

        // get plugin module base names
        $plugins = array();

        foreach ($files as $file) {
            if (strpos($file, '.') === 0) {
                continue;
            }
            $t = str_replace('PNPlugin', '', $file);
            $t = str_replace('Array.class.php', '', $t);
            $plugins[$file] = $t;
        }

        return $plugins;
    }

    function getSelectorDataActive($all=true)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $selector = array();
        if ($all) {
          $selector[-1] = __('All', $dom);
        }
        $selector[0] = __('Inactive', $dom);
        $selector[1] = __('Active', $dom);

        return $selector;
    }

    function getSelectorDataApproved($all=true)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $selector = array();
        if ($all) {
          $selector[-1] = __('All', $dom);
        }
        $selector[0] = __('Not Approved', $dom);
        $selector[1] = __('Approved', $dom);

        return $selector;
    }

    function getSelectorDataArchiveExpire()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $selector = array(
            0  => __('Never', $dom),
            1  => __('1 Month', $dom),
            2  => __('2 Months', $dom),
            3  => __('3 Months', $dom),
            6  => __('6 Months', $dom),
            12 => __('1 Year', $dom)
        );

        return $selector;
    }


    function getSelectorDataLanguage($all=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $languages = ZLanguage::getInstalledLanguageNames();
        if ($all) {
            $languages = array_merge(array('' => __('All', $dom)), $languages);
        }

        return $languages;
    }


    function getSelectorDataNewsletterFrequency($all=false)
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

    function getSelectorDataNewsletterType($all=false)
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

    function getSelectorDataSendDay()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $selector = array(
            '1' => __('Monday', $dom),
            '2' => __('Tuesday', $dom),
            '3' => __('Wednesday', $dom),
            '4' => __('Thursday', $dom),
            '5' => __('Firday', $dom),
            '6' => __('Saturday', $dom),
            '0' => __('Sunday', $dom)
        );

        return $selector;
    }

    function scandir($directory, $ignoreFiles=null, $matchString=null)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $files = array();

        if (!$directory) {
            LogUtil::registerError(__("Invalid [directory] received", $dom));
            return $files;
        }

        if (!file_exists($directory)) {
            LogUtil::registerError (__f("Directory [%s] does not seem to exist", $directory, $dom));
            return $files;
        }

        if (is_file($directory)) {
            LogUtil::registerError (__f("Directory [%s] seems to be a file and not a directory", $directory, $dom));
            return $files;
        }

        $files = scandir($directory);

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
