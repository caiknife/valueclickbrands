#!/usr/bin/php -q
<?php
function fast_startElemHandler($parser, $name, $attribs=array()) {
    global $results, $listing_id, $cur_tag, $next, $prev;
    array_push($cur_tag, $name);

    switch($name) {
        case "RESULT":
            $listing_id = $attribs["NUM"];
            $results[$listing_id]->title = "";
            $results[$listing_id]->description = "";
            $results[$listing_id]->domain = "";
            $results[$listing_id]->ClickUrl = "";
            $results[$listing_id]->abstract = "";
            break;
        case "PREV": $prev=""; break;
        case "NEXT": $next=""; break;
    }
}

function fast_endElemHandler($parser, $name, $attribs=array()) {
    global $results, $listing_id, $cur_tag;
    if($name=="RESULT") $listing_id=0;
    elseif($name=="URL") {
        $res=parse_url($results[$listing_id]->ClickUrl);
        $results[$listing_id]->domain = strtolower($res["host"]);
    }
    array_pop($cur_tag);
}

function fast_charDataHandler($parser, $data) {
    global $results, $listing_id, $next, $prev, $cur_tag;
    switch ($cur_tag[count($cur_tag)-1]) {
        case "NEXT": $next .= $data; break;
        case "PREV": $prev .= $data; break;
        case "TITLE": if($listing_id) $results[$listing_id]->title .= $data; break;
        case "DESCRIPTION": if($listing_id) $results[$listing_id]->description .= $data; break;
        case "ABSTRACT": if($listing_id) $results[$listing_id]->abstract .= $data; break; 
        case "TERM": if($listing_id) $results[$listing_id]->abstract .= $data; break;
        case "BIGSEP": if($listing_id) $results[$listing_id]->abstract .= "..."; break;
        case "MEDSEP": if($listing_id) $results[$listing_id]->abstract .= ".."; break;
        case "SMALLSEP": if($listing_id) $results[$listing_id]->abstract .= "."; break;
        case "SEP": if($listing_id) $results[$listing_id]->abstract .= "<br/>/n"; break;
        case "URL": if($listing_id) $results[$listing_id]->ClickUrl .= $data; break;
    }
}

function send_fast_request($req_params) {
    $request = "/std_xml_d00?".$req_params;
    if($fp = @fsockopen("fastsmarter-proxy.idp.inktomisearch.com", 8075, &$errno, &$errstr, 10)) {
        fputs($fp, "GET ".$request." HTTP/1.0\r\n");
        fputs($fp, "Host: fastsmarter-proxy.idp.inktomisearch.com\r\n");
        fputs($fp, "Accept: */*\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "\r\n");
    }
    return $fp;
}

function get_fast_answer($fp, &$next_params) {
    if(!$fp) return array();
    global $results, $next, $prev, $listing_id, $cur_tag; // necessary for xml processing functions
    $response="";
    $status = socket_get_status($fp);
    while(!feof($fp)) {
        $response .= fgets($fp, 1024);
        $status = socket_get_status($fp);
    }
    fclose($fp);
    if($status["timed_out"]) {

        if (!count($response)){
           return array();
        }
    }
    if(preg_match("`^.+?\r\n\r\n(.+)$`s", $response, $res)) $response=$res[1];

    $results=array();
    $listing_id=0;
    $next=""; $prev="";
    $cur_tag=array();

    $parser = xml_parser_create();
    xml_set_element_handler($parser, "fast_startElemHandler", "fast_endElemHandler");
    xml_set_character_data_handler($parser, "fast_charDataHandler");
    if(!xml_parse($parser, $response, true)) {
         return array();
    }
    xml_parser_free($parser);

    $next_params = preg_replace("`^.*?\?(.*)$`", "\\1", $next);

    foreach ($results as $key => $listing) {
        $l_descr = trim($listing->description);
        if ( ($l_descr=="") || empty($l_descr) ) {
           $results[$key]->description=$results[$key]->abstract;
        }
        $col_b = substr_count($results[$key]->description,"<b>");
        $col_bb= substr_count($results[$key]->description,"</b>");
        $col_d = strlen($results[$key]->description) - ( ($col_b+$col_bb)*3+$col_bb);

        if ( $col_d > __CFG_OUTPUT_DESC_LENGTH ) {
             $results[$key]->description = trim( substr($results[$key]->description, 0, __CFG_OUTPUT_DESC_LENGTH+(($col_b+$col_bb)*3+$col_bb-3)) );
             preg_match("/^(.*?)[,;:.<>]*\s[^ ]+$/s" , $results[$key]->description, $arr_match);
             $results[$key]->description = trim($arr_match[1])."...";
        }
    }
    return $results;
}


$TypeSearch = empty($TypeSearch) ? "all" : $TypeSearch;
$searchType = empty($searchType) ? "" : $searchType;
$fast_params = array( "query"=>$searchText." +lang.primary:en".($adult_filter?" -fast.type:offensive":""), // +domain:com
                                       "type"=>"all", "collapse"=>"true", "hits"=>(11-$PageRec));
foreach($fast_params as $key => $val){
  switch(strtolower($key)) {
  case "hits": $val=(11-$PageRec);
  case "query":
  case "type":
  case "offset":
  case "collapse": $fast_request .= "$key=".urlencode($val)."&";
  }
}
if(($length=strlen($fast_request)) && ($fast_request[$length-1]=="&")){
  $fast_request = substr($fast_request, 0, $length-1);
  $fast_connection = send_fast_request($fast_request);
  $fast_next_params = "";
  $fast_results = get_fast_answer($fast_connection, $fast_next_params);
}

foreach($fast_results as $a=>$b)
{
        echo $a." | ".$b->title."\n";
}

?>
