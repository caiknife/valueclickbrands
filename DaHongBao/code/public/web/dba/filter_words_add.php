<?php
/*
 * Created on 2006-10-25
 * filter_words_add.php
 * -------------------------
 * 
 * 
 * 
 * @author Fan Xu
 * @copyright  (C) 2004-2006 Mezimedia.com
 * @license    http://www.mezimedia.com  PHP License 5.0
 * @version    CVS: $Id: filter_words_add.php,v 1.1 2013/04/15 10:57:29 rock Exp $
 * @link       http://www.smarter.com/
 */

$words_add = array();
//取出数据,并消重
$data = file_get_contents("words_add.txt");
$data_arr = split("\n", $data);
//print_r($data_arr);
foreach ($data_arr as $sWord) {
    $sWord = trim($sWord); //! remove the last "\n" or "\r\n"
    if (empty($sWord)) {
        continue;
    }
    $words_add[$sWord] = 1;
}
//保存结果
$str = "";
foreach ($words_add as $sWord => $val) {
    $sWord = trim($sWord); //! remove the last "\n" or "\r\n"
    if (empty($sWord)) {
        continue;
    }
    $str .= $sWord . "\r\n";
}
$f = fopen("words_add_swap.txt", "w+");
fwrite($f, $str);
fclose($f);
print_r($words_add);
echo "\nlen=".strlen($str);
//切换
copy("words_add_swap.txt", "words_add.txt");
?>