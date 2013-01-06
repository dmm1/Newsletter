<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

if (!class_exists('Newsletter_DBObject_User')) {
    return LogUtil::registerError (__('Unable to load array class [user]', $dom));
}

class Newsletter_DBObject_ImportArray extends Newsletter_DBObject_UserArray
{
    var $_format;
    var $_outputToFile;
    var $_pagesize;

    function Newsletter_DBObject_ImportArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_UserArray();
        $this->_objSort      = 'email';
        $this->_delimeter    = FormUtil::getPassedValue ('delimeter', '|', 'GETPOST');
        $this->_filename     = FormUtil::getPassedValue ('filename', '', 'GETPOST');
        $this->_format       = FormUtil::getPassedValue ('format', 'xml', 'GETPOST');
        $this->_pagesize     = 100;

        if (!$this->_filename) {
            $this->_filename = 'NewsletterUsers.' . $this->_format;
        }

        $this->_init($init, $where);
    }

    function getWhere ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $rc = true;

        // check auth key
        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0, 'GET'), 'GET');
        $masterKey = (string)ModUtil::getVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            $rc = LogUtil::registerError (__('Invalid admin_key received', $dom));
        }

        // validate input file format
        if ($rc) {
            if ($this->_format == 'xml' && strtolower(substr($this->_filename, -4)) != '.xml') {
                $rc = LogUtil::registerError (__("Invalid filename [$this->_filename]. ImportGeneric with format=XML must export to a XML filename", $dom));
            }
            if ($this->_format == 'csv' && strtolower(substr($this->_filename, -4)) != '.csv') {
                $rc = LogUtil::registerError (__("Invalid filename [$this->_filename]. ImportGeneric with format=CSV must export to a CSV filename", $dom));
            }
        }

        // export
        if ($rc) {
            $cnt = 0;
            $insertCnt = 0;
            $updateCnt = 0;
            $txt = '';
            if ($this->_format == 'xml') {
                $data = $this->_importXML ();
            } elseif ($this->_format == 'csv') {
                $data = $this->_importCSV ();
            } else {
                $rc = LogUtil::registerError (__("Invalid format [$this->_format] received in ImportGeneric", $dom));
            }

            $cnt = count ($data);
            LogUtil::registerStatus (__("Read $cnt records for [newsletter_users]", $dom));

            foreach ($data as $dat) {
                if (!is_numeric($dat['id'])) {
                    continue;
                }
                $email  = DataUtil::formatForStore ($dat['email']);
                $rCount = DBUtil::selectObjectCount ('newsletter_users', "nlu_email = '$email'");
                if (!$rCount) {
                    $sqlRC = DBUtil::insertObject ($dat, 'newsletter_users');
                    $insertCnt++;
                } else {
                    $sqlRC = DBUtil::updateObject ($dat, 'newsletter_users');
                    $updateCnt++;
                }
                if (!$sqlRC) {
                    break;
                }
            }
        }

        LogUtil::registerStatus("Inserted $insertCnt records");
        LogUtil::registerStatus("Updated $updateCnt records");

        // construct a meaningful name from type
        $name = 'UserImportReturnCode';

        // get errors and determine success
        $errors   = LogUtil::getErrorMessages(true, false);
        $messages = LogUtil::getStatusMessages(true, false, false);
        $success  = $errors ? 0 : 1;

        $xml        = '<?xml version="1.0" encoding="ISO-8859-15"?>' . "\n";
        $xml       .= "<$name>\n";
        $xml       .= "  <success>$success</success>\n";
        if ($ot1) {
            $xml   .= "  <type>User</type>\n";
        }
        if ($filename) {
            $xml    .= "  <filename>$filename</filename>\n";
        }

        $search = array ('<i>', '</i>', '<b>', '</b>');
        if ($messages) {
            $xml .= "  <messages>\n";
            foreach ($messages as $message) {
                $msg  = str_replace ($search, '', $message);
                $xml .= "    <message>$msg</message>\n";
            }
            $xml .= "  </messages>\n";
        }

        if ($errors) {
            $xml .= "  <errors>\n";
            foreach ($errors as $error) {
                $err  = str_replace ($search, '', $error);
                $xml .= "    <error>$error</error>\n";
            }
            $xml .= "  </errors>\n";
        }

        $xml       .= "</$name>\n";
        header('Content-type: text/xml');
        print $xml;
        exit();
    }

    function _importCSV ()
    {
        $fName = "modules/Newsletter/import/$this->_filename";
        if (!file_exists($fName)) {
            return LogUtil::registerError (__("Import file [$fName] does not exist", $dom));
        }
        if (!is_readable($fName)) {
            return LogUtil::registerError (__("Import file [$fName] can not be read", $dom));
        }

        $data  = file_get_contents($fName);
        $lines = explode ("\n", $data);
        if (!$lines) {
            return LogUtil::registerError (__("Empty string read from import file [$fName]", $dom));
        }

        $colArray = DBUtil::getColumnsArray('newsletter_users');
        if (!$colArray) {
            $rc = LogUtil::registerError (__("Unable to load column array for [newsletter_users]", $dom));
        }

        $data       = array();
        foreach ($lines as $line) {
            if (!$line) {
                continue;
            }
            $dat = array();
            $fields = explode ($this->_delimeter, $line);
            foreach ($colArray as $col) {
                $dat[$col] = $fields[$cnt++];
            } 
            $data[] = $dat;
        }
        
        return $data;
    }

    function _importXML ()
    {
        $fName = "modules/Newsletter/import/$this->_filename";
        if (!file_exists($fName)) {
            return LogUtil::registerError (__("Import file [$fName] does not exist", $dom));
        }
        if (!is_readable($fName)) {
            return LogUtil::registerError (__("Import file [$fName] can not be read", $dom));
        }

        $xmlString = file_get_contents ($fName);
        if (!$xmlString) {
            return LogUtil::registerError (__("Empty string read from import file [$this->_filename]", $dom));
        }

        $xml = simplexml_load_string ($xmlString);
        if (!$xml) {
            return LogUtil::registerError (__("XML Parse failed from import file [$this->_filename]", $dom));
        }

        $colArray = DBUtil::getColumnsArray('newsletter_users');
        if (!$colArray) {
            $rc = LogUtil::registerError (__("Unable to load column array for [newsletter_users]", $dom));
        }

        // try to get the root node
        $data       = array();
        foreach ($xml->user as $item) {
            $dat = array();
            foreach ($colArray as $col) {
                $val = html_entity_decode((string)$item->$col);
                $dat[$col] = $val;
            } 
            $data[] = $dat;
        }

        return $data;
    }
}
