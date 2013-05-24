<?php
echo "start: " . microtime() . "\n";
$dbid = dba_open("dict_swp.db", "r", "db4");
$rInfoKey = array();
$key = dba_firstkey($dbid);
do {
    $value = dba_fetch($key, $dbid);
    if (!isset($rInfoKey[strlen($key)])) {
        $rInfoKey[strlen($key)] = 0;
    }
    $rInfoKey[strlen($key)]++;
    if (!isset($rInfoValue[$value])) {
        $rInfoValue[$value] = 0;
    }
    $rInfoValue[$value]++;
} while (false != ($key = dba_nextkey($dbid)));
dba_close($dbid);
echo "ended: " . microtime() . "\n";
print_r($rInfoKey);
print_r($rInfoValue);
?>
