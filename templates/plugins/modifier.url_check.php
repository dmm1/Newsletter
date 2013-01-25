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

 /** OLD VERSION
 * Smarty modifier format the output of data
 *
 * Changes links to include baseURL
 *
 * Example
 *  {$content|url_check}
 *
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
/*function smarty_modifier_url_check($data)
{
    $domain = System::getBaseUrl();
    
    $changed = false;
    $tagcount = preg_match_all("/<a(.*)>(.*)<\/a>/si", $data, $tags);
    for ($i=0; $i<$tagcount; $i++) {
        if (!preg_match('/^(http:\/\/)?([^\/]+)/i',$tags['0'][$i])) {
            $changed = true;
            $linkstring[$i] = preg_replace("/\<a href=(.)(.*?)(.)>(.*?)<\/a>/", '<a href=$1'.$domain.'$2$3>$4</a>', $tags['0'][$i]);
        }
    }    

    if ($changed) {
        $data = preg_replace(array("/<a(.*)>(.*)<\/a>/si"), $linkstring, $data);
    }
    
    $changed = false;
    $tagcount = preg_match_all("/<img(.*)>/si", $data, $imgtags);
    for ($i=0; $i<$tagcount; $i++) {
        if (!preg_match('/^(http:\/\/)?([^\/]+)/i',$tags['0'][$i])) {
            $changed = true;
            $imgstring[$i] = preg_replace("/\<img src=(.)(.*?)(.)(.*?)>/", '<img src=$1'.$domain.'$2$3$4>', $imgtags['0'][$i]);
        }
    }    

    if ($changed) {
        $data = preg_replace(array("/<img(.*)>/si"), $imgstring, $data);
    }
    
    return $data;
}
*/
