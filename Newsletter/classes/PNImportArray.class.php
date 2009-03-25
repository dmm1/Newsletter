<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

if (!Loader::loadArrayClassFromModule('Newsletter', 'user')) {
    return LogUtil::registerError ('Unable to load array class [user]');
}

class PNImportArray extends PNUserArray
{
    var $_format;
    var $_outputToFile;
    var $_pagesize;

    function PNImportArray($init=null, $where='')
    {
        $this->PNUserArray();
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
        $masterKey = (string)pnModGetVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            $rc = LogUtil::registerError ('Invalid admin_key received');
        }

        // validate input file format
        if ($rc) {
            if ($this->_format == 'xml' && strtolower(substr($this->_filename, -4)) != '.xml') {
                $rc = LogUtil::registerError ("Invalid filename [$this->_filename]. ImportGeneric with format=XML must export to a XML filename");
            }
            if ($this->_format == 'csv' && strtolower(substr($this->_filename, -4)) != '.csv') {
                $rc = LogUtil::registerError ("Invalid filename [$this->_filename]. ImportGeneric with format=CSV must export to a CSV filename");
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
                $rc = LogUtil::registerError ("Invalid format [$this->_format] received in ImportGeneric");
            }

            $cnt = count ($data);
            LogUtil::registerStatus ("Read $cnt records for [newsletter_users]");

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

        LogUtil::registerStatus ("Inserted $insertCnt records");
        LogUtil::registerStatus ("Updated $updateCnt records");

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
            return LogUtil::registerError ("Import file [$fName] does not exist");
        }
        if (!is_readable($fName)) {
            return LogUtil::registerError ("Import file [$fName] can not be read");
        }

        $data  = file_get_contents($fName);
        $lines = explode ("\n", $data);
        if (!$lines) {
            return LogUtil::registerError ("Empty string read from import file [$fName]");
        }

        $colArray = DBUtil::getColumnsArray('newsletter_users');
        if (!$colArray) {
            $rc = LogUtil::registerError ("Unable to load column array for [newsletter_users]");
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
            return LogUtil::registerError ("Import file [$fName] does not exist");
        }
        if (!is_readable($fName)) {
            return LogUtil::registerError ("Import file [$fName] can not be read");
        }

        $xmlString = file_get_contents ($fName);
        if (!$xmlString) {
            return LogUtil::registerError ("Empty string read from import file [$this->_filename]");
        }

        $xml = simplexml_load_string ($xmlString);
        if (!$xml) {
            return LogUtil::registerError ("XML Parse failed from import file [$this->_filename]");
        }

        $colArray = DBUtil::getColumnsArray('newsletter_users');
        if (!$colArray) {
            $rc = LogUtil::registerError ("Unable to load column array for [newsletter_users]");
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

