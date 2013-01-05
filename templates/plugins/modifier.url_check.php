<?php

 /**
 * Smarty modifier format the output of data
 *
 * changes links to include baseURL
 *
 * Example
 *  <!--[$content|url_check]-->
 *
 * 
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_url_check($data)
{
    $domain = System::getBaseUrl();
    
    $changed = false;
    $tagcount = preg_match_all("/<a(.*)>(.*)<\/a>/si", $data, $tags);
    for ($i=0; $i<$tagcount; $i++) {
        if (!preg_match('/^(http:\/\/)?([^\/]+)/i',$tags['0'][$i])) {
            $changed = true;
            $linkstring[$i] = preg_replace("/\<a href=(.)(.*?)(.)>(.*?)<\/a>/", 
            '<a href=$1'.$domain.'$2$3>$4</a>',
            strtolower($tags['0'][$i]));
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
            $imgstring[$i] = preg_replace("/\<img src=(.)(.*?)(.)(.*?)>/", '<img src=$1'.$domain.'$2$3$4>', strtolower($imgtags['0'][$i]));
        }
    }    

    if ($changed) {
        $data = preg_replace(array("/<img(.*)>/si"), $imgstring, $data);
    }
    
    return $data;
}

