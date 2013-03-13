<?php
 /**
 * Smarty modifier format the output of data
 *
 * Treats <img> tags in content for proper display in Outlook, sets max size

 *
 * Example
 *  {$content|nlTreatImg}
 *
 * @since        2013-03-05
 * @param        $data     the contents to transform
 * @return       string    the modified output
 */
function smarty_modifier_nlTreatImg($data, $width = null, $height = null, $retainratio = true, $noenlargeorig = true, $noenlargesized = true)
{
    $defaultSize = 300; // become setting?
    if (!isset($width)) {
        $width = $defaultSize;
    }
    if (!isset($height)) {
        $height = $defaultSize;
    }
    if ($data) {
        include_once 'modules/Newsletter/vendor/DOMDocumentUtil.php';
        // put base url, reduce size for larger images
        $imgSize = array('width' => $width, 'height' => $height, 'retainratio' => $retainratio, 'noenlargeorig' => $noenlargeorig, 'noenlargesized' => $noenlargesized);
        $data = DOMDocumentUtil::imgTagConvert($data, null, false, System::getBaseUrl(), $imgSize);
    }

    return $data;
}
