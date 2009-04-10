README for the Newsletter Module
--------------------------------


Newsletter Sending:
-------------------
Standard: 
index.php?module=Newsletter&func=send&scheduled=1&authKey={Newsletter admin auth key}

If Multlingual Mode is turned on: 
index.php?module=Newsletter&func=send&scheduled=1&lang={lang}&authKey={Newsletter admin auth key}

Supported Languages:
---------------------
English
German
Dutch

Export Functionality:
---------------------

Export files are generated to the directory modules/Newsletter/export

index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}
or
index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}&outputToFile=0
or
index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}&outputToFile=0&filename=MyExportFile.xml
or 
index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}&outputToFile=0&format=csv
or 
index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}&outputToFile=0&format=csv&filename=MyExportFile.csv
or 
index.php?module=Newsletter&ot=export&authKey={Newsletter admin auth key}&outputToFile=0&format=csv&filename=MyExportFile.csv&delimeter=, (for CSV with explicit delimeter)


Import Functionality: 
---------------------

Import files are read from the directory modules/Newsletter/import

index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}
or
index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}&format=xml
or
index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}&format=xml&filename=MyImportFile.xml
or
index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}&format=csv
or
index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}&format=csv&filename=MyImportFile.csv
or
index.php?module=Newsletter&type=admin&func=view&ot=import&authKey={Newsletter admin auth key}&format=csv&filename=MyImportFile.csv&delimeter=, (for CSV with explicit delimeter)
