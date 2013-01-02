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

if (!is_class('Newsletter_DBObject_User')) {
    return LogUtil::registerError (__('Unable to load array class [user]'), $dom);
}

class Newsletter_DBObject_ExportArray extends Newsletter_DBObject_UserArray
{
    var $_format;
    var $_outputToFile;
    var $_pagesize;

    function Newsletter_DBObject_ExportArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_UserArray();
        $this->_objSort      = 'email';
        $this->_delimeter    = FormUtil::getPassedValue ('delimeter', '|', 'GETPOST');
        $this->_filename     = FormUtil::getPassedValue ('filename', '', 'GETPOST');
        $this->_format       = FormUtil::getPassedValue ('format', 'xml', 'GETPOST');
        $this->_outputToFile = FormUtil::getPassedValue ('outputToFile', 1, 'GETPOST');
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
        $adminKey  = (string)FormUtil::getPassedValue ('admin_key', FormUtil::getPassedValue('authKey', 0), 'GETPOST');
        $masterKey = (string)ModUtil::getVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            $rc = LogUtil::registerError (__('Invalid admin_key received', $dom));
        }

        // validate output file format
        if ($rc) {
            if ($this->_outputToFile) {
                if ($this->_format == 'xml' && strtolower(substr($this->_filename, -4)) != '.xml') {
                    $rc = LogUtil::registerError (__("Invalid filename [$this->_filename]. ExportGeneric with format=XML must export to a XML filename", $dom));
                }
                if ($this->_format == 'csv' && strtolower(substr($this->_filename, -4)) != '.csv') {
                    $rc = LogUtil::registerError (__("Invalid filename [$this->_filename]. ExportGeneric with format=CSV must export to a CSV filename", $dom));
                }
            }
        }

        // get column array
        if ($rc) {
            $colArray = DBUtil::getColumnsArray('newsletter_users');
            if (!$colArray) {
                $rc = LogUtil::registerError(__("Unable to load column array for [newsletter_users]", $dom));
            }
        }

        // export
        if ($rc) {
            $cnt = 0;
            $txt = '';
            if ($this->_format == 'xml') {
                $txt = $this->_exportXML ($cnt);
            } elseif ($this->_format == 'csv') {
                $txt = $this->_exportCSV ($cnt);
            } else {
                $rc = LogUtil::registerError (__("Invalid format [$this->_format] received in ExportGeneric", $dom));
            }

            $bytes = strlen($txt);
            LogUtil::registerStatus (__("Exported $cnt records ($bytes bytes) for ot [newsletter_users]", $dom));
            $filename = 'modules/Newsletter/export/NewsletterUsers.' . $this->_format;


            // output to browser
            if (!$this->_outputToFile) {
                if ($this->_format == 'xml') {
                    header('Content-type: text/xml');
                    print $txt;
                } elseif ($this->_format == 'csv') {
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-disposition: attachment; filename=data.csv");
                    print $txt;
                } else {
                    exit (__("Invalid format [$format] recevied in saveResult ... exiting", $dom));
                }
                exit();
            } else {
                $fp = fopen ($filename, 'w');
                if (!$fp) {
                    LogUtil::registerError (__("Error opening file [$filename] for writing", $dom));
                } else {
                    $rc = fwrite ($fp, $txt);
                    if (!$rc) {
                        LogUtil::registerError (__("Error writing to file [$filename]", $dom));
                    }
                fclose ($fp);
                }

            }
        }

        // construct a meaningful name from type
        $name = 'UserExportReturnCode';

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
            $host    = System::serverGetVar('HTTP_HOST');
            $baseuri = System::getBaseUri();
            $xml    .= "  <filenameURL>http://$host/$baseuri/$filename</filenameURL>\n";
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

    function selectPostProcess ($data=null) 
    {
        if (!$data) {
        $data = $this->_objData;
        }

        $class = 'Newsletter_DBObject_User';
        if (!class_exists($class)) {
            LogUtil::registerError (__('Unable to load class [user] ... disabling input post-processing for array class', $dom));
        } else {
            $obj = new Newsletter_DBObject_User ();
            foreach ($data as $k=>$v) {
                $obj->setData ($v);
                $data[$k] = $obj->selectPostProcess ($v);
            }
        }

        $this->_objData = $data;
        return $this->_objData;
    }


    /* The XML format generated is as follows: 

       <?xml version="1.0" encoding="ISO-8859-15"?>
       <users>
         <user>
           <id>[value]</id>
           <{field}>[value]</{field}>
           ....
         </user>
         <user>
           ....
         </user>
       </users>

      where the order of the fields in the individual object entries corresponds 
      to the order in which these fields are listed in pntables.php
    */
    // this function uses text to build the xml because it seems that XMLWriter truncates output on large files
    function _exportXML (&$cnt)
    {
        $colArray   = DBUtil::getColumnsArray('newsletter_users');
        $otSingle   = 'user';
        $otMultiple = 'users';

        $cnt  = 0;
        $page = 0;
        $xml  = '<?xml version="1.0" encoding="ISO-8859-15"?>' . "\n";
        $xml .= "<$otMultiple>\n";
        do {
            $data = $this->select ('', $this->_objSort, $page*$this->_pagesize, $this->_pagesize);
            if ($data === false) {
                $rc = false;
                break;
            }

            foreach ($data as $dat) {
                $xml .= " <$otSingle>\n";
                foreach ($colArray as $field) {
                    if ($this->_complete || (!$this->_complete && $dat[$field] !== '')) {
                        if ($this->_fieldSetName && $this->_fieldSetValue!=null && isset($fieldSetNames[$field])) {
                            $idx = $fieldSetNames[$field];
                            $val = htmlentities($fieldSetValues[$idx]);
                        } else {
                            $val  = htmlentities($dat[$field]);
                        }
                        $xml .= "   <$field>$val</$field>\n";
                    }
                }
                $xml .= " </$otSingle>\n";
                if (isset($colArray[$this->_exportedColName]) && !$this->_allRecords) {
                    $this->_exportedIDs[] = $dat['id'];
                }
                $cnt++;
            }
            $page++;
        } while ($data);
        $xml .= "</$otMultiple>\n";

        return $xml;
    }

    /* The CSV format generated is as follows: 

       col1|col2|col3|col4|...|coln\n
       val1|val2|val3|val4|...|valn\n
       ...
       val1|val2|val3|val4|...|valn\n

      where the order of the fields on each line to the order in which these fields are listed in pntables.php
    */
    function _exportCSV (&$cnt)
    {
        $txt      = '';
        $colArray   = DBUtil::getColumnsArray('newsletter_users');

        foreach ($colArray as $field) {
            $txt .= $field . $this->_delimeter;
        }
        $txt .= "\n";

        $cnt = 0;
        $page = 0;
        do {
            $data = $this->select ('', $this->_objSort, $page*$this->_pagesize, $this->_pagesize);
            if ($data === false) {
                $rc = false;
                break;
            }

            foreach ($data as $dat) {
                foreach ($colArray as $field) {
                    $txt .= $dat[$field]. $this->_delimeter;
                }
                if (isset($colArray[$this->_exportedColName]) && !$this->_allRecords) {
                    $this->_exportedIDs[] = $dat['id'];
                }
                $txt .= "\n";
                $cnt++;
            }
            $page++;
        } while ($data);

        return $txt;
    }
}
