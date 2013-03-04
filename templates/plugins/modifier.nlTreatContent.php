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
        $view = Zikula_View::getInstance();

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
            // If <img> tag exist, treat style for proper display in Outlook
            $pos = strpos($data, "<img");
            if ($pos !== false) {
                include_once 'modules/Newsletter/vendor/DOMDocumentUtil.php';
                $data = DOMDocumentUtil::imgStyleConvert($data);
            }
        }
        $data = trim($data);

        // strip BBcode
        $data = preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $data);

        // truncate if length is set
        $data = nl_truncate($data, $truncate);

        // url_check
        require_once $view->_get_plugin_filepath('modifier','url_check');
        $data = smarty_modifier_url_check($data);
    }

    if ($htmlOutput) {
        return DataUtil::formatForDisplayHTML($data);
    } else {
        return html_entity_decode($data, ENT_QUOTES, 'UTF-8');
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
