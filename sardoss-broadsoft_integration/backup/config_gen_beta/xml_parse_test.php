<?php
$parser=xml_parser_create();
$fp=fopen("test.xml","r");

$data=fread($fp,4096);

echo filesize($fp);
  
xml_parse_into_struct($parser,$data,$values);
xml_parser_free($parser);

print_r($values);

echo "<br><br><br>" . $values[Order][iadName];
?> 