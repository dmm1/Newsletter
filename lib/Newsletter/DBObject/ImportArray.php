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

$dom = ZLanguage::getModuleDomain('Newsletter');

if (!class_exists('Newsletter_DBObject_User')) {
    return LogUtil::registerError (__f('Unable to load array class [%s]', 'user', $dom));
}

class Newsletter_DBObject_ImportArray extends Newsletter_DBObject_UserArray
{
    var $_format;
    var $_file;
    var $_filename;

    public function __construct($init=null, $where='')
    {
        parent::__construct();
        $this->_objSort   = 'email';
        $this->_delimeter = FormUtil::getPassedValue ('delimeter', ';', 'GETPOST');
        $this->_file      = FormUtil::getPassedValue ('file', '', 'FILES');
        $this->_format    = FormUtil::getPassedValue ('format', 'xml', 'GETPOST');
        $this->_filename  = $this->_file['name'];
        $this->_init($init, $where);
    }

    public function getWhere ($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

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
        
        unlink($this->_file['tmp_name']);

        return System::redirect(ModUtil::url('Newsletter', 'admin', 'view', array('ot' => 'userimport')));
    }

    public function _importCSV ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $fName = $this->_file['tmp_name'];

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

        $data = array();
        foreach ($lines as $lineNumber => $line) {
            if (!$line || $lineNumber == 0) {
                continue;
            }
            $dat = array();
            $fields = explode ($this->_delimeter, $line);
            $cnt = 0;
            foreach ($colArray as $col) {
                $dat[$col] = $fields[$cnt++];
            } 
            $data[] = $dat;
        }
        return $data;
    }

    public function _importXML ()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $fName = $this->_file['tmp_name'];

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
