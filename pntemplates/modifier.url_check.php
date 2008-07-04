<?php

 /**
 * Smarty modifier format the output of data
 *
 * changes links to include baseURL
 *
 * Example
 *
 *ÊÊ <!--[$content|url_check]-->
 *
 * 
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_url_check($data)
{
	$domain = pnGetBaseURL();
	preg_match_all('/ src="([^"]+)"/', $data, $urls);
	foreach ($urls[1] as $i=>$url) {
		if (!preg_match('|^http://|',$url)) {
			$replace_url = str_replace($url,$domain.$url,$urls[0][$i]);
			$data = str_replace ($urls[0][$i],$replace_url,$data);
		}
	}
	preg_match_all('/ background="([^"]+)"/',$data,$urls);
	foreach ($urls[1] as $i=>$url) {
		if (!preg_match('|^http://|',$url)) {
			$replace_url = str_replace($url,$domain.$url,$urls[0][$i]);
			$data = str_replace ($urls[0][$i],$replace_url,$data);
		}
	}
	
	preg_match_all('|<a [^>]*href="(.*)"|Ui',$data,$urls);
	foreach ($urls[1] as $i => $url) {
		if (!preg_match('|^http://|', $url) && !preg_match('|^mailto:|',$url)) {
		    $replace_url = str_replace($url,$domain.$url, $urls[0][$i]);
		    $data = str_replace ($urls[0][$i],$replace_url, $data);
		}
	}
	
    return $data;
}

?>
