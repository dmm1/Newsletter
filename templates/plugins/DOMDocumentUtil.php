<?php
/**
 * DOMDocument Utilities
 *
 * Handles HTML fragments.
 *
 * Methods
 *      imgStyleConvert - Treat img tags in HTML fragment.
 *                        Suitable to prepare HTML for HTML emails.
 *                        (Outlook 2000-2013 can't recognize style in <img> tag)
 *
 * Use: DOMDocumentExact (in same file).
 *
 * Usage: DOMDocumentUtil::imgStyleConvert($html)
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
     * Treat img tags in HTML fragment.
     * Some style attributes are set as img attributes.
     *      float: value => align="value"
     *      width: valuepx => width="value"
     *      height: valuepx => height="value"
     *
     * @param string $html
     * @param string $encoding (optional, default UTF-8)
     * @param boolean $removeStyle (optional)
     * @return string
     */
    public static function imgStyleConvert($html, $encoding = null, $removeStyle = false)
    {
        if (empty($encoding)) {
            $encoding = 'UTF-8';
        }

        $DOMDoc = new DOMDocumentExact();
        $DOMDoc->loadHTMLbody($html, $encoding);
        foreach($DOMDoc->getElementsByTagName('img') as $img) {
            $style = $img->getAttribute('style');
            $arrStyle = explode(';', $style);
            foreach ($arrStyle as $styleAttr) {
                $arrAttribute = explode(':', $styleAttr);
                if (trim($arrAttribute[0]) == 'float') {
                    $attrName = 'align';
                    if (!$img->getAttribute($attrName)) {
                        $img->setAttribute($attrName, trim($arrAttribute[1]));
                    }
                } elseif (trim($arrAttribute[0]) == 'width') {
                    $attrName = 'width';
                    if (!$img->getAttribute($attrName)) {
                        $img->setAttribute($attrName, (int)$arrAttribute[1]);
                    }
                } elseif (trim($arrAttribute[0]) == 'height') {
                    $attrName = 'height';
                    if (!$img->getAttribute($attrName)) {
                        $img->setAttribute($attrName, (int)$arrAttribute[1]);
                    }
                }
            }
            if ($removeStyle) {
                $img->removeAttribute('style');
            }
        }

        return $DOMDoc->saveHTMLbody();
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
    * @return string
    */
    public function saveHTMLbody($preserveBodytagIfExist = true)
    {
        $html = $this->saveHTML();
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
