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
    public static function getActivePlugins()
    {
        $plugins = self::getPluginClasses();

        foreach ($plugins as $k=>$plugin) {
            $active = ModUtil::getVar('Newsletter', "plugin_$plugin", false);
            if (!$active) {
            #    unset($plugins[$k]);
            }
        }
        return $plugins;
    }

    public static function getPluginClasses()
    {
        $modules = ModUtil::getModulesByState(ModUtil::STATE_ACTIVE);
        
        $files = array();
        $plugins = array();

        foreach($modules as $module)
        {
            $basedir = ModUtil::getModuleBaseDir($module['name']);
            $files = FileUtil::getFiles($basedir . '/' . $module['name'] . '/lib/' . $module['name'] . '/NewsletterPlugin');
            
            
            foreach ($files as $key => $file) {
                if (strpos($file, '.') === 0) {
                    continue;
                }
                $file = str_replace('.php', '', $file);
                $files[$key] = $module['name'] . "_NewsletterPlugin_" . $file;
            }
            $plugins = array_merge($plugins, $files);

            #echo $basedir . '/' . $module['name'] . '/lib/' . $module['name'] . '/NewsletterPlugin<br/>';
            #echo "<pre>" . print_r($files) . "</pre><br />";
        }
        return $plugins;
    }

    public static function getSelectorDataActive($all=true)
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

    public static function getSelectorDataApproved($all=true)
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

    public static function getSelectorDataArchiveExpire()
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


    public static function getSelectorDataLanguage($all=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $languages = ZLanguage::getInstalledLanguageNames();
        if ($all) {
            $languages = array_merge(array('' => __('All', $dom)), $languages);
        }

        return $languages;
    }


    public static function getSelectorDataNewsletterFrequency($all=false)
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

    public static function getSelectorDataNewsletterType($all=false)
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

    public static function getSelectorDataSendDay()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $selector = array(
            '1' => __('Monday', $dom),
            '2' => __('Tuesday', $dom),
            '3' => __('Wednesday', $dom),
            '4' => __('Thursday', $dom),
            '5' => __('Friday', $dom),
            '6' => __('Saturday', $dom),
            '0' => __('Sunday', $dom)
        );

        return $selector;
    }

    public static function getSelectorHookUserRegistration()
    {
        $selector = array(
            "checkboxon"  => __('Checkbox checked by default'),
            "checkboxoff" => __('Checkbox not checked by default'),
            "infmessage"  => __('Info message only'),
            "nomessage"   => __('No special message')
        );
        
        return $selector;
    }

    public static function convertSelectorArrayForFormHandler($array)
    {
        $outArray = array();
        
        foreach($array as $value => $text) {
            $outArray[] = array('text' => $text, 'value' => $value);
        }
        
        return $outArray;
    }
}
