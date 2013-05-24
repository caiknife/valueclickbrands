<?php
    /// open dba handle ///
    require_once ("../etc/const.inc.php");
	require_once(__INCLUDE_ROOT."lib/util/class.FileDistribute.php");
    if(DIRECTORY_SEPARATOR == "/") { //linux
    	$hDba = dba_open(__SETTING_FULLPATH."dba/dict_swp.db", "n", "db4");
    } else {
    	$hDba = dba_open(__SETTING_FULLPATH."dba/dict_swp.db", "nl", "db3");
    }
    if($hDba == false) {
    	die('can not open dict_swp.db');
    }
    /// stop words dic ///
    $f = fopen("words_stop.txt", "r");
    $nValueNew = 6; //! 110
    while ($sWord = fgets($f)) {
        $sWord = trim($sWord); //! remove the last "\n" or "\r\n"
        if (empty($sWord)) {
            continue;
        }
        @dba_insert($sWord, $nValueNew, $hDba);
    }
    fclose($f);
    /// base words dic ///
    $f = fopen("words_base.txt", "r");
    while ($sWord = fgets($f)) {
        $sWord = trim($sWord); //! remove the last "\n" or "\r\n"
        if (empty($sWord)) {
            continue;
        }
        $length = strlen($sWord);
        $sSubWord = "";
        for ($i = 0; $i < $length; $i++) {
            $sSubWord .= $sWord[$i] . $sWord[$i+1];
            $i++;
            $bMore = ($length <= ($i + 1) ? 0 : 1);
            $bWord = ($bMore ? 0 : 1) ;
            $bStop = 0;
            if ($nValueOld = dba_fetch($sSubWord, $hDba)) {
                $nValueNew = $nValueOld | (($bStop<<2) + ($bWord<<1) + ($bMore));
                if ($nValueNew != $nValueOld) {
                    dba_replace($sSubWord, $nValueNew, $hDba);
                }
            }
            else {
                dba_insert($sSubWord, (($bStop<<2) + ($bWord<<1) + ($bMore)), $hDba);
            }
        }
    }
    fclose($f);
    /// additional words dic ///
    $f = fopen("words_add.txt", "r");
    while ($sWord = fgets($f)) {
        $sWord = trim($sWord); //! remove the last "\n" or "\r\n"
        if (empty($sWord)) {
            continue;
        }
        $length = strlen($sWord);
        $sSubWord = "";
        for ($i = 0; $i < $length; $i++) {
            $sSubWord .= $sWord[$i] . $sWord[$i+1];
            $i++;
            $bMore = ($length <= ($i + 1) ? 0 : 1);
            $bWord = ($bMore ? 0 : 1);
            $bStop = 0;
            if ($nValueOld = dba_fetch($sSubWord, $hDba)) {
                $nValueNew = $nValueOld | (($bStop<<2) + ($bWord<<1) + ($bMore));
                if ($nValueNew != $nValueOld) {
                    dba_replace($sSubWord, $nValueNew, $hDba);
                }
            }
            else {
                dba_insert($sSubWord, (($bStop<<2) + ($bWord<<1) + ($bMore)), $hDba);
            }
        }
    }
    fclose($f);
    /// close dba handle ///
    dba_close($hDba);
	copy(__SETTING_FULLPATH."dba/dict_swp.db", __SETTING_FULLPATH."dba/dict.db");
	FileDistribute::syncFile();
echo "DONE";	
?>
