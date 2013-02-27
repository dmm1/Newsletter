<?php
 /**
 * Smarty modifier format the output of data
 *
 * Changes links to include baseURL
 *
 * Example
 *  {$content|url_check}
 *
 * @since        2013-01-22
 * @param        $data     the contents to transform
 * @return       string    the modified output
 */
function smarty_modifier_url_check($data)
{
    $baseurl = System::getBaseUrl();
    if (empty($baseurl)) {
        return $data;
    }
    if(substr($baseurl, -1) != '/') {
        $baseurl .= '/';
    }
    $baseurlparts = parse_url($baseurl);

    $keyattributes = array('href="', 'src="', 'background="', "url('");
    $keylinks1 = 'http://';
    $keylinks2 = 'https://';
    $keylinks3 = 'ftp://';
    $keylinks4 = 'mailto://';

    foreach ($keyattributes as $key){
        $new_txt = '';
        while ($pos = strpos($data, $key)) {
            $pos += strlen($key);
            if (substr($data, $pos, strlen($keylinks1)) != $keylinks1 && substr($data, $pos, strlen($keylinks2)) != $keylinks2 && substr($data, $pos, strlen($keylinks3)) != $keylinks3 && substr($data, $pos, strlen($keylinks4)) != $keylinks4) {
                // it is not absolute url, add $baseurl
                $new_baseurl = $baseurl;
                if (substr($data, $pos, 1) == '/') {
                    $new_baseurl = $baseurlparts['scheme'].'://'.$baseurlparts['host'];
                }
                $new_txt .= substr($data, 0, $pos).$new_baseurl;
            } else {
                // it is absolute url
                $new_txt .= substr($data, 0, $pos);
            }
            $data = substr($data, $pos);
        }
        $data = $new_txt.$data;
    }

    return $data;
}
