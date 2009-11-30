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

class PNExportArray extends PNUserArray
{
    var $_format;
    var $_outputToFile;
    var $_pagesize;

    function PNExportArray($init=null, $where='')
    {
        $this->PNUserArray();
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
        $masterKey = (string)pnModGetVar ('Newsletter', 'admin_key', -1);
        if ($adminKey != $masterKey) {
            $rc = LogUtil::registerError ('Invalid admin_key received');
        }

        // validate output file format
        if ($rc) {
            if ($this->_outputToFile) {
                if ($this->_format == 'xml' && strtolower(substr($this->_filename, -4)) != '.xml') {
                    $rc = LogUtil::registerError ("Invalid filename [$this->_filename]. ExportGeneric with format=XML must export to a XML filename");
                }
                if ($this->_format == 'csv' && strtolower(substr($this->_filename, -4)) != '.csv') {
                    $rc = LogUtil::registerError ("Invalid filename [$this->_filename]. ExportGeneric with format=CSV must export to a CSV filename");
                }
            }
        }

        // get column array
        if ($rc) {
            $colArray = DBUtil::getColumnsArray('newsletter_users');
            if (!$colArray) {
                $rc = LogUtil::registerError ("Unable to load column array for [newsletter_users]");
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
                $rc = LogUtil::registerError ("Invalid format [$this->_format] received in ExportGeneric");
            }

            $bytes = strlen($txt);
            LogUtil::registerStatus ("Exported $cnt records ($bytes bytes) for ot [newsletter_users]");
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
                    exit ("Invalid format [$format] recevied in saveResult ... exiting");
                }
                exit();
            } else {
                $fp = fopen ($filename, 'w');
                if (!$fp) {
                    LogUtil::registerError ("Error opening file [$filename] for writing");
                } else {
                    $rc = fwrite ($fp, $txt);
                    if (!$rc) {
                        LogUtil::registerError ("Error writing to file [$filename]");
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
            $host    = pnServerGetVar('HTTP_HOST');
            $baseuri = pnGetBaseURI();
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

        if (!Loader::loadClassFromModule('Newsletter', 'user')) {
            LogUtil::registerError ('Unable to load class [user] ... disabling input post-processing for array class');
        } else {
            $obj = new PNUser ();
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

