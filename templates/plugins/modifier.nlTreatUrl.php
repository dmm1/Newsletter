<?php
/**
 * Smarty modifier to add the Piwik campaign parameters to an url
 *
 * Example
 *  {$content|nlTreatUrl}
 *
 * @since 2014-06-27
 * @param string $url
 *
 * @return string
 */
function smarty_modifier_nlTreatUrl($url)
{
    $campaignName = ModUtil::getVar('Newsletter', 'tracking_campaign', null);
    $campainKeyword = ModUtil::getVar('Newsletter', 'tracking_keyword', null);
    if (empty($campaignName)) {
        return $url;
    }
    $campaignName .= "-" . DateUtil::getDatetime_Date();

    $url = rtrim($url, '/');
    if (strpos($url, '?') === false) {
        $url .= '?';
    } else {
        $url .= '&';
    }

    $url .= "pk_campaign=" . urlencode($campaignName);
    if (!empty($campainKeyword)) {
        $url .= "&pk_kwd=" . urlencode($campainKeyword);
    }

    return $url;
}
