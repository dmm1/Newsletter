<?php
 /**
 * Smarty modifier format the output of data
 *
 * Treats the content according to given settings
 *
 * Example
 *  {$content|nlTreatContent:'News'}
 *
 * @since        2013-02-26
 * @param        $data     the contents to transform
 * @return       string    the modified output
 */
function smarty_modifier_nlTreatContent($data, $pluginName, $htmlOutput = true)
{
    if ($data) {
        $arrSettings = explode(";", ModUtil::getVar('Newsletter', 'plugin_'.$pluginName.'_Settings', '0;400'));
        $treatType = (int)$arrSettings[0];
        $truncate = (int)$arrSettings[1];

        if ($truncate == 0) {
            // this is sign that content is not shown
            return '';
        }

        if ($htmlOutput) {
            if ($treatType == 1) {
                // nl2br (from text only)
                $data = nl2br($data);
            } elseif ($treatType == 2) {
                // strip_tags (from html)
                $data = strip_tags($data);
            } elseif ($treatType == 3) {
                // strip_tags but <img><a>
                $data = strip_tags($data, '<img><a>');
            }
        }
        $data = trim($data);

        // truncate if length is set
        $data = nl_truncate($data, $truncate);

        // url_check
        include_once __DIR__.'/modifier.url_check.php';
        $data = smarty_modifier_url_check($data);
    }

    if ($htmlOutput) {
        return DataUtil::formatForDisplayHTML($data);
    } else {
        return html_entity_decode($data);
    }
}

// simplified copy from Zikula smarty_modifier_truncate
function nl_truncate($string, $length = 80, $etc = '...')
{
    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));

        return substr($string, 0, $length) . $etc;
    }
    
    return $string;
}
