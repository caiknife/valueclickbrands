<?php
$str = "Ïà»úÉÁ´æ¿¨";
$result = "";

echo "start: " . microtime() . "\n";
$dbid = dba_open("dict.db", "r", "db4");

echo "str: " . $str . "\n\n";

$mode = "";
$sub = "";
$start = -1;
$word = false;
for ($i = 0; $i < strlen($str); $i++) {
    if (127 < ord($str[$i])) {
        if ("CHINESE" != $mode) {
            $result .= (" " == $word ? "" : $word . "/");
            $sub = "";
            $word = false;
            $start = $i - 1;
            $mode = "CHINESE";
        }
        $sub .= $str[$i] . $str[$i+1];
        $i++;
        $value = dba_fetch($sub, $dbid);
        switch ($value) {
        case false: //! no found
            if ($word) {
                $result .= (" " == $word ? "" : $word . "/");
                $sub = "";
                $word = false;
                $i = $start;
            }
            else {
                $result .= $sub[0] . $sub[1] . "/";
                $sub = "";
                $start += 2;
                $i = $start;
            }
            break;
        case 1: //! 001
            break;
        case 2: //! 010
            $result .= $sub . "/";
            $sub = "";
            $word = false;
            $start = $i;
            break;
        case 3: //! 011
            $word = $sub;
            $start = $i;
            break;
        case 6: //! 110
            $sub = "";
            $word = false;
            $start = $i;
            break;
        case 7: //! 111
            if (!$word) {
                $word = " "; //! mark the first stop word.
            }
            $start = $i;
            break;
        default:
            break;
        }
    }
    elseif (ereg("[a-zA-Z_]", $str[$i])) {
        $result .= ($word ? $word . "/" : "");
        $sub = "";
        $word = false;
        $i = $start;
    }
    elseif (ereg("[0-9]", $str[$i])) {
    }
    else {
    }
echo "s[" . $sub . "] v[" . $value . "] w[" . $word . "] r[" . $result . "]\n";
//if ($i > 5) die;
}
dba_close($dbid);
$result .= ($word ? $word . "/" : "");
for ($i = $start + 1; $i < strlen($str); $i++) {
    $result .= $str[$i] . $str[$i+1] . "/";
    $i++;
}
echo "\nresult: " . $result . "\n";
echo "ended: " . microtime() . "\n";
?>
