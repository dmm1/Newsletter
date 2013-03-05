<?php
 /**
 * Smarty modifier format the output of data
 *
 * Treats <a> tags in content and convert to simple links, put base url.
 *      To be used when sending text only emails.
 *
 * Example
 *  {$content|nlTreatAtag}
 *
 * @since        2013-03-05
 * @param        $data     the contents to transform
 * @return       string    the modified output
 */
function smarty_modifier_nlTreatAtag($data, $toSipleLink = true)
{
    if ($data) {
        include_once 'modules/Newsletter/vendor/DOMDocumentUtil.php';
        // put base url, convert to simple links
        $data = DOMDocumentUtil::aTagConvert($data, null, System::getBaseUrl(), $toSipleLink);
    }

    return $data;
}
