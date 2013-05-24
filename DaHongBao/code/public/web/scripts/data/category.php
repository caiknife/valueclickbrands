<?php
	require_once("links.inc.php");
	require_once(__INCLUDE_ROOT."etc/const.inc.php");
	require_once(__INCLUDE_ROOT."lib/classes/class.Category.php");

$oCategory = new Category($actCat);
echo "<html>\n";
echo "<head>\n";
echo "<script language=\"JavaScript\">\n";
echo "<!--\n";
if ( $oCategory->get("Category_") > 0 ){
   echo "window.location.replace('".__LINK_ROOT.(str_replace(' ','_',$oCategory->get("Name"))).".html".($source!="" ? "?source=".$source : "")."');\n";
}
else{
   echo "window.location.replace('".__LINK_ROOT.($source!="" ? "?source=".$source : "")."');\n";
}
echo "//-->\n";
echo "</script>\n";
echo "</head>\n";
echo "</html>\n";

?>
