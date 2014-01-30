<?php
/**
 * DOMDocument Utilities
 *
 * Handles HTML fragments.
 *
 * Methods
 *      imgTagConvert - Treat <img> tags in HTML fragment.
 *                        Suitable to prepare HTML for HTML emails.
 *                        (Outlook 2000-2013 can't recognize style in <img> tag)
 *      aTagConvert - Treat <a> tags in HTML fragment.
 *                        Suitable to prepare HTML for HTML emails.
 *      putBaseurl - Put a specified base url, if  not exist in given url.
 *      stripBaseurl - Strip base url.
 *
 * Use: DOMDocumentExact (in same file).
 *
 * Usage: DOMDocumentUtil::imgTagConvert($html)
 *        For example see test.php in the package.
 *
 * @author     (c) Nikolay Petkov, http://www.cmstory.com/
 * @location   https://github.com/nmpetkov/DOMDocumentUtil
 * @license    GNU/GPL
 * @version    1.0
 */
class DOMDocumentUtil
{
    /**
     * Treat <img> tags in HTML fragment.
     * Some style attributes are set as img attributes.
     *      float: value => align="value"
     *      width: valuepx => width="value"
     *      height: valuepx => height="value"
     *
     * @param string $html
     * @param string $encoding (optional, default UTF-8)
     * @param boolean $removeStyle (optional)
     * @param string $baseurl (optional) - Base URL to put in src attribute (if relative path is given for the src)
     * @param array $arrSize (optional) - Force size for the image, accepted array elements:
     *          width (integer)
     *          height (integer)
     *          retainratio (boolean)
     *          noenlargeorig (boolean) - flag not to set size bigger then size of original image
     *          noenlargesized (boolean) - flag not to set size bigger then already set size
     * @param array $arrStyle (optional) - Force or set style for the image, accepted array elements:
     *          float (left, right or none)
     *          float_force (0, 1) - if to force given style, in case it exists in the tag, default 0 (not to force))
     * @return string
     */
    public static function imgTagConvert($html, $encoding = null, $removeStyle = false, $baseurl = null, $arrSize = null, $arrStyle = null)
    {
        if (empty($encoding)) {
            $encoding = 'UTF-8';
        }

        $DOMDoc = new DOMDocumentExact();
        $DOMDoc->loadHTMLbody($html, $encoding);
        foreach($DOMDoc->getElementsByTagName('img') as $img)
        {
            $src = trim($img->getAttribute('src'));

            // Base URL treatment
            if (!empty($baseurl)) {
                if ($src) {
                    $baseurlset = false;
                    $src = self::putBaseurl($src, $baseurl, array('http://', 'https://'), $baseurlset);
                    if ($baseurlset) {
                        $img->setAttribute('src', $src);
                    }
                }
            }

            // Image size treatment
            $setWidth = 0;
            $setHeight = 0;
            if (isset($arrSize)) {
                $imageSize = @getimagesize($src, $imageInfo);
                $imgWidth = $imageSize[0];
                $imgHeight = $imageSize[1];
                if ($imgWidth && $imgHeight) {
                    if ($arrSize['width']) {
                        // width for the image is given to be set
                        $setWidth = $arrSize['width'];
                    }
                    if ($arrSize['height']) {
                        // height for the image is given to be set
                        $setHeight = $arrSize['height'];
                    }
                    if ($arrSize['noenlargeorig']) {
                        // max dimensions are given (not to enlarge image)
                        if ($setWidth > $imgWidth) {
                            $setWidth = $imgWidth;
                        }
                        if ($setHeight > $imgHeight) {
                            $setHeight = $imgHeight;
                        }
                    }
                    if ($arrSize['retainratio'] && $setWidth && $setHeight) {
                        // retain ration for size is given
                        if ($imgWidth > $imgHeight) {
                            $setHeight = round($setWidth * $imgHeight / $imgWidth);
                        } else {
                            $setWidth = round($setHeight * $imgWidth / $imgHeight);
                        }
                    }
                }
            }

            // Style treatment
            $style = $img->getAttribute('style');
            $arrStyleTag = explode(';', $style);
            if (isset($arrStyle)) {
                // force or set given float style
                if (isset($arrStyle['float']) && $arrStyle['float']) {
                    $stylefound = false;
                    foreach (array_keys($arrStyleTag) as $k) {
                        $arrAttribute = explode(':', $arrStyleTag[$k]);
                        if (trim($arrAttribute[0]) == 'float') {
                            if (isset($arrStyle['float_force']) && $arrStyle['float_force']) {
                                $arrStyleTag[$k] = 'float: '.$arrStyle['float'];
                            }
                            $stylefound = true;
                        }
                    }
                    if (!$stylefound) {
                        $arrStyleTag[] = 'float: '.$arrStyle['float'];
                    }
                }
            }
            foreach (array_keys($arrStyleTag) as $k) {
                $arrAttribute = explode(':', $arrStyleTag[$k]);
                if (trim($arrAttribute[0]) == 'float') {
                    if (!$img->getAttribute('align')) {
                        $img->setAttribute('align', trim($arrAttribute[1]));
                    }
                } elseif (trim($arrAttribute[0]) == 'width') {
                    $styleWidth = (int)$arrAttribute[1];
                    if ($setWidth && ($arrSize['noenlargesized'] && $styleWidth > $setWidth)) {
                        // set in style
                        $arrStyleTag[$k] = 'width: '.$setWidth.'px';
                    } else {
                        // get from style to set as img attribute
                        $setWidth = $styleWidth;
                    }
                    if ($arrSize['retainratio'] && $imgHeight && $imgWidth) {
                        $arrStyleTag[$k] = 'height: '.round($setWidth * $imgHeight / $imgWidth).'px';
                    }
                } elseif (trim($arrAttribute[0]) == 'height') {
                    $styleHeight = (int)$arrAttribute[1];
                    if ($setHeight && ($arrSize['noenlargesized'] && $styleHeight > $setHeight)) {
                        // set in style
                        $arrStyleTag[$k] = 'height: '.$setHeight.'px';
                    } else {
                        // get from style to set as img attribute
                        $setHeight = $styleHeight;
                    }
                    if ($arrSize['retainratio'] && $imgHeight && $imgWidth) {
                        $arrStyleTag[$k] = 'width: '.round($setHeight * $imgWidth / $imgHeight).'px';
                    }
                }
            }

            // Set calculated sizes as image attributes
            $attrWidth = $img->getAttribute('width');
            $attrHeight = $img->getAttribute('height');
            if ($setWidth && (!$attrWidth || ($arrSize['noenlargesized'] && $attrWidth > $setWidth))) {
                $img->setAttribute('width', $setWidth);
            }
            if ($setHeight && (!$attrHeight || ($arrSize['noenlargesized'] && $attrHeight > $setHeight))) {
                $img->setAttribute('height', $setHeight);
            }

            // Remove or set the style
            if ($removeStyle) {
                $img->removeAttribute('style');
            } else {
                $style = implode(';', $arrStyleTag);
                if ($style) {
                    $img->setAttribute('style', $style);
                }
            }
        }

        return $DOMDoc->saveHTMLbody();
    }

    /**
     * Treat <a tags in HTML fragment.
     * Put base url, convert to simple links if given
     *
     * @param string $html
     * @param string $encoding (optional, default UTF-8)
     * @param string $baseurl (optional) - Base URL to put in href attribute (if relative path is given for the href)
     * @return string
     */
    public static function aTagConvert($html, $encoding = null, $baseurl = nulll, $toSimpleLink = false)
    {
        if (empty($encoding)) {
            $encoding = 'UTF-8';
        }

        $DOMDoc = new DOMDocumentExact();
        $DOMDoc->loadHTMLbody($html, $encoding);
        $docElements = $DOMDoc->getElementsByTagName('a');
        // here have to use regressive loop, otherwise not all elements will be replaced!
        $i = $docElements->length - 1;
        while ($i > -1) {
            $a = $docElements->item($i);
            $href = trim($a->getAttribute('href'));

            // Base URL treatment
            if (!empty($baseurl)) {
                if ($href) {
                    $baseurlset = false;
                    $href = self::putBaseurl($href, $baseurl, array('http://', 'https://', 'ftp://', 'mailto://'), $baseurlset);
                    if ($baseurlset) {
                        $a->setAttribute('href', $href);
                    }
                }
            }

            // Convert to simple link
            if ($toSimpleLink) {
                // text is same if link text is same
                if (trim(self::stripBaseurl($a->nodeValue), '/') == trim(self::stripBaseurl($href), '/')) {
                    $textnodeValue = $a->nodeValue;
                } else {
                    $textnodeValue = $a->nodeValue . ($href ? ' ('.$href.')' : '');
                }
                $textnode = $DOMDoc->createTextNode($textnodeValue);
                $a->parentNode->replaceChild($textnode, $a);
            }

            $i--;
        }

        return $DOMDoc->saveHTMLbody();
    }

    /**
     * Strip base url
     *
     * @param string $baseurl
     * @param array $keyUrls - keys to check and strip
     * @param boolean $baseurlstripped (output) - if base url is stripped
     * @return string
     */
    public static function stripBaseurl($url, $keyUrls = array('http://', 'https://'), &$baseurlstripped = false)
    {
        foreach ($keyUrls as $key) {
            if (substr(strtolower($url), 0, strlen($key)) == $key) {
                $url = str_replace($key, '', $url);
                $baseurlstripped = true;
                break;
            }
        }

        return $url;
    }

    /**
     * Put base url
     *
     * @param string $baseurl
     * @param array $keyUrls - keys to check if base url is not already put
     * @param boolean $baseurlset (output) - if base url is put
     * @return string
     */
    public static function putBaseurl($url, $baseurl, $keyUrls = array(), &$baseurlset = false)
    {
        $baseurlset = true;
        foreach ($keyUrls as $key) {
            if (substr(strtolower($url), 0, strlen($key)) == $key) {
                $baseurlset = false;
                break;
            }
        }

        if ($baseurlset) {
            if (substr($url, 0, 1) == '/') {
                $url = trim($baseurl, '/') . $url;
            } else {
                $url = trim($baseurl, '/') .'/'. $url;
            }
        }

        return $url;
    }
}

/**
 * This class extends DOMDocument class, available in PHP 5.
 * (More info about DOMDocument: http://php.net/manual/en/class.domdocument.php)
 *
 * Main problem with DOMDocument is the fact, that it handles whole HTML pages, 
 * including <html> and <body> tags.
 *
 * This class, DOMDocumentExact, overcomes this limitation, and can handle any HTML fragments.
 *
 * Methods
 *      loadHTMLbody - Wrap for DOMDocument::loadHTML, resolving encoding issue.
 *                     Encoding is added as second parameter and defaults to UTF-8.
 *
 *      saveHTMLbody - Wrap for DOMDocument::saveHTML, resolving adding extra tags
 *                     Optional parameter permits handling HTML parts wrapped with <body> tag
 *                     $saveType optional parameter
 *                       XML (default) - preserves self-closing tags closing slash: <br /> <img .../>
 *                       HTML - removes closing slash (old HTML format): <br> <img ...>
 *
 * Usage: See DOMDocumentUtil.
 *
 * @author     (c) Nikolay Petkov, http://www.cmstory.com/
 * @location   https://github.com/nmpetkov/DOMDocumentUtil
 * @license    GNU/GPL
 * @version 1.0
 */
class DOMDocumentExact extends DOMDocument
{
    /**
    * For internal use
    */
    private $bodyTagExist = false;

    /**
    * Load HTML fragment
    * Encoding is implemented by putting <head> section with specifying proper charset
    *
    * @param string $html
    * @param string $encoding (optional, default UTF-8)
    * @return string
    */
    public function loadHTMLbody($html, $encoding = "UTF-8")
    {
        $pos = strpos($html, "<body");
        if ($pos === false) {
            $html = '<body>' .$html. '</body>';
        } else {
            $this->bodyTagExist = true;
        }
        $pos = strpos($html, "<head");
        if ($pos === false) {
            $html = '<head><meta http-equiv="Content-Type" content="text/html; charset='.$encoding.'" /></head>' . $html;
        }
        $pos = strpos($html, "<html");
        if ($pos === false) {
            $html = '<html>' .$html. '</html>';
        }
        // @ suppresses warnings, if HTML fragment is not healthy
        @parent::loadHTML($html);
    }

    /**
    * Return HTML between or including <body> tag
    *
    * @param boolean $preserveBodytagIfExist (optional)
    * $saveType string $saveType (optional):
    *                  XML (default) - preserves self-closing tags closing slash: <br /> <img .../>
    *                  HTML - removes closing slash (old HTML format): <br> <img ...>
    * @return string
    */
    public function saveHTMLbody($preserveBodytagIfExist = true, $saveType = 'XML')
    {
        if ($saveType == 'XML') {
            $html = $this->saveXML();
        } else {
            $html = $this->saveHTML();
        }
        $pos = strpos($html, "<body");
        if ($pos === false) {
            $body = $html;
        } else {
            if ($preserveBodytagIfExist && $this->bodyTagExist) {
                $start_pos = $pos;
                $end_pos = strpos($html, "</body>", $start_pos) + 7;
            } else {
                $start_pos = strpos($html, ">", $pos) + 1;
                $end_pos = strpos($html, "</body>", $start_pos);
            }
            $body = substr($html, $start_pos, $end_pos - $start_pos);
        }

        return $body;
    }
}
