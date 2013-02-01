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
    public static function encodeText ($string)
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

    public static function getActivePlugins ()
    {
        $plugins = self::getPluginClasses();

        foreach ($plugins as $k=>$plugin) {
            $active = ModUtil::getVar('Newsletter', "plugin_$plugin", false);
            if (!$active) {
                unset($plugins[$k]);
            }
        }

        return $plugins;
    }

    public static function getPluginClasses()
    {
        $ignoreFiles = array();
        $ignoreFiles[] = 'Plugin.php';
        $ignoreFiles[] = 'PluginArray.php';
        $ignoreFiles[] = 'PluginBaseArray.php';

        $files = self::scandir('modules/Newsletter/lib/Newsletter/DBObject', $ignoreFiles, 'Plugin');

        // get plugin module base names
        $plugins = array();

        foreach ($files as $file) {
            if (strpos($file, '.') === 0) {
                continue;
            }
            $t = str_replace('Plugin', '', $file);
            $t = str_replace('Array.php', '', $t);
            $plugins[$file] = $t;
        }

        return $plugins;
    }

    public static function getSelectorDataActive($all=true)
    {
        $selector = array();
        if ($all) {
          $selector[-1] = __('All');
        }
        $selector[0] = __('Inactive');
        $selector[1] = __('Active');

        return $selector;
    }

    public static function getSelectorDataApproved($all=true)
    {
        $selector = array();
        if ($all) {
          $selector[-1] = __('All');
        }
        $selector[0] = __('Not Approved');
        $selector[1] = __('Approved');

        return $selector;
    }

    public static function getSelectorDataArchiveExpire()
    {
        $selector = array(
            0  => __('Never'),
            1  => __('1 Month'),
            2  => __('2 Months'),
            3  => __('3 Months'),
            6  => __('6 Months'),
            12 => __('1 Year')
        );

        return $selector;
    }


    public static function getSelectorDataLanguage($all=false)
    {
        $languages = ZLanguage::getInstalledLanguageNames();
        if ($all) {
            $languages = array_merge(array('' => __('All')), $languages);
        }

        return $languages;
    }


    public static function getSelectorDataNewsletterFrequency($all=false)
    {
        $array = array();
        if ($all) {
          $array[-1] = __('All');
        }
        $array[0]  = __('Weekly');
        $array[1]  = __('Monthly');
        $array[2]  = __('Every 2 Months');
        $array[3]  = __('Every 3 Months');
        $array[6]  = __('Every 6 Months');
        $array[9]  = __('Every 9 Months');
        $array[12] = __('Yearly');

        return $array;
    }

    public static function getSelectorDataNewsletterType($all=false)
    {
        $array = array();
        if ($all) {
          $array[0] = __('All');
        }
        $array[1] = __('Text');
        $array[2] = __('HTML');
        $array[3] = __('Text with Link to Archive');

        return $array;
    }

    public static function getSelectorDataSendDay()
    {
        $selector = array(
            '1' => __('Monday'),
            '2' => __('Tuesday'),
            '3' => __('Wednesday'),
            '4' => __('Thursday'),
            '5' => __('Firday'),
            '6' => __('Saturday'),
            '0' => __('Sunday')
        );

        return $selector;
    }

    public static function scandir($directory, $ignoreFiles=null, $matchString=null)
    {
        $files = array();

        if (!$directory) {
            LogUtil::registerError(__f("Empty [%s] received.", 'directory'));
            return $files;
        }

        if (!file_exists($directory)) {
            LogUtil::registerError (__f("Directory [%s] does not seem to exist.", $directory));
            return $files;
        }

        if (is_file($directory)) {
            LogUtil::registerError (__f("Directory [%s] seems to be a file nor a directory.", $directory));
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
