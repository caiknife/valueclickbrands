function add_bookmark(){
   var url="http://www.dahongbao.com"
   var title="大红包 - www.dahongbao.com"
   if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)){
      if (confirm('添加大红包至收藏夹？')){
         window.external.AddFavorite (url,title);
      }
   }
   else{
      var msg = "对不起，收藏夹添加失败！！";
      if (navigator.appName == "Netscape") msg += " (Press <CTRL+D>)";
      alert(msg);
   }
}
function getCookie(name){
   var result = null;
   var myCookie = " " + document.cookie + ";";
   var searchName = " " + name + "=";
   var startOfCookie = myCookie.indexOf(searchName);
   var endOfCookie;

   if (startOfCookie != -1){
      startOfCookie += searchName.length;
      endOfCookie = myCookie.indexOf(";",startOfCookie);
      result = unescape(myCookie.substring(startOfCookie,endOfCookie));
   }
   return result;
}
function setCookie(name,value,path,domain,expires,secure){
   var expString  = ((expires == null) ? "" : ("; expires=" + expires.toGMTString()));
   var pathString  = ((path == null) ? "" : ("; path=" + path));
   var domainString = ((domain == null) ? "" : ("; domain=" + domain));
   var secureString = ((secure == true) ? "; secure" : "");
   document.cookie = name + "=" + escape(value) + expString + pathString + domainString + secureString;
}
function todayDate(){
   var today = new Date();
   return getMonthName(today) + ' ' + today.getDate() + ', ' + today.getFullYear();
}
function getMonthName(dat){
   var m = new Array(12);
   m[0] = "January";
   m[1] = "February";
   m[2] = "March";
   m[3] = "April";
   m[4] = "May";
   m[5] = "June";
   m[6] = "July";
   m[7] = "August";
   m[8] = "September";
   m[9] = "October";
   m[10] = "November";
   m[11] = "December";
   return m[dat.getMonth()];
}
function check_mail(vemail){
   if (vemail.value == ""){
      alert("请输入Email地址");
      return false;
   }
   else{
      var gem = vemail.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
      if (!gem){
         alert("Email地址无效");
         vemail.focus();
         vemail.select();
         return false;
      }
      else{
         return true;
      }
   }
}
function MM_preloadImages() { //v3.0
   var d = document;
   if( d.images ) {
      if( !d.MM_p ) d.MM_p = new Array();
      var i,j = d.MM_p.length,a = MM_preloadImages.arguments;
      for( i=0; i < a.length; i++ )
      if ( a[i].indexOf( "#" ) !=0 ) {
         d.MM_p[j] = new Image;
         d.MM_p[j++].src=a[i];
      }
   }
}
function preloadImages(name,num,tp) {
   for (i=0; i<num.length; i++){
      MM_preloadImages(name + num[i] + 'on.png',name + num[i] + 'off.' + tp);
   }
}
function preloadImagesS(name) {
   MM_preloadImages(name);
}
function changeImageState(img_name,img_path,state,tp) {
   if (document.images) {
      //document.images[img].src = 'images/menu/'+img + state +'.gif';
      document.images[img_name].src = img_path + img_name + state + '.' + tp;
   }
}

function popup(mid) {
   var popupOnExit  = top.MyClose;
   if ( popupOnExit && (mid>0) ) {
      var group  = getCookie("SOURCEGROUP");
      if ( group ) {
         var m_count = top.myArray.length;
         for (var i= 0; i< m_count; i++) {
            if ((group.toLowerCase() == top.myArray[i].toLowerCase())){
               //alert('http://' + top.document.location.hostname + '/redir.php?m='+mid);
               window.open('http://' + top.document.location.hostname + '/redir.php?m='+mid);
               break;
            }
         }
      }
   }
}

function check_merchant(merchant){
  set_source();
  var group   = getCookie('SOURCEGROUP');
  var m_count = myArray.length;
  for (var i= 0; i< m_count; i++) {
     if ((group.toLowerCase() == myArray[i].toLowerCase())){
       window.open('/redir.php?m='+merchant);

     }
  }
}

function popdown(serverroot,slist) {
   if (!getCookie("popdown")){
      var today = new Date();
      setCookie("popdown","popdownvalue","/",".dahongbao.com",new Date(today.getTime() + (23-today.getUTCHours())*3600000));
      if ( top.document.location.search.indexOf('source=') > 0 ){
         source = top.document.location.search.substr(top.document.location.search.indexOf('source=')+7);
         if ( source.indexOf('&') > 0 ){
            source = source.substr(0,source.indexOf('&'));
         }
         for (var j=0; j<slist.length; j++){
            if (slist[j] == source){
               window.open('http://' + document.location.hostname + '/popdown.html', 'POPDOWN', 'innerWidth=150,innerHeight=150,width=150,height=150,resizable=no,scrollbars=no,location=no');
            }
         }
      }
      else{
         if ( getCookie("SOURCE") ){
            source = getCookie("SOURCE");
            for (var j=0; j<slist.length; j++){
               if (slist[j] == source){
                  window.open('http://' + document.location.hostname + '/popdown.html', 'POPDOWN', 'innerWidth=150,innerHeight=150,width=150,height=150,resizable=no,scrollbars=no,location=no');
               }
            }
         }
      }
      self.focus();
   }
}

function popdownframe(vvv,slist) {
   if (!getCookie("popdown")){
      var today = new Date();
      setCookie("popdown","popdownvalue","/",".dahongbao.com",new Date(today.getTime() + (23-today.getUTCHours())*3600000));
      if ( vvv == 1 ){
//         self.document.location.href = 'http://' + self.document.location.hostname + '/hot_coupons.html';
         self.document.location.replace('http://' + self.document.location.hostname + '/hot_coupons.html');
         return;
      }
      else{
         if ( top.document.location.search.indexOf('source=') > 0 ){
            source = top.document.location.search.substr(top.document.location.search.indexOf('source=')+7);
            if ( source.indexOf('&') > 0 ){
               source = source.substr(0,source.indexOf('&'));
            }
            for (var j=0; j<slist.length; j++){
               if (slist[j] == source && slist[j] != ''){
                  return;
               }
            }
         }
         else{
            if ( getCookie("SOURCE") ){
               source = getCookie("SOURCE");
               for (var j=0; j<slist.length; j++){
                  if (slist[j] == source && slist[j] != ''){
                  }
               }
            }
         }
         window.open('http://' + document.location.hostname + '/hot_coupons.html', 'POPDOWN', 'width=720,height=300,resizable=no,scrollbars=no,location=no');
      }
   }
//   self.document.location.href = 'http://' + self.document.location.hostname + '/hot_coupons_cm.html';
   self.document.location.replace('http://' + self.document.location.hostname + '/hot_coupons_cm.html');
}

function show_restriction(text){
   if ( text == "" ){
      text = '没有任何限制！';
   }
   rwin = window.open('','','scrolbars=no,resizable=no,width=250,height=150');
   rwin.document.write('<html><head><title>优惠券适用范围</title></head><body bgcolor="#6666CC"><table height="100%" width="100%"><tr><td style="font-family:Arial,Helvetica,sans-serif; font-size:9pt;color:#FFFFFF;">' + text + '</td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a style="font-family:Arial,Helvetica,sans-serif; font-size:9pt; color:#000000;" href="JavaScript:self.close();">关闭</a></td></tr></table></body></html>');
}

// @note Rename it from make_stat to orig_make_stat
function orig_make_stat(category,merchant,coupon,is_frame){
   var today = new Date();
   var newsess = 0;
   var CSession = getCookie("SESSION");
   if ( !CSession ){
      newsess = 1;
      setCookie("SESSION","1","/",".dahongbao.com");
   }

   var referer;
   var keyword;
   var source;
   referer = top.document.referrer;
   var keyword  = '';
   if ( referer.indexOf('//') >= 0 ){
      referer = referer.substr(referer.indexOf("//")+2);
   }
   if ( referer.indexOf('/') >= 0 ){
      referer = referer.substr(0,referer.indexOf("/"));
   }
   if ( referer.indexOf('?') >= 0 ){
      referer = referer.substr(0,referer.indexOf("?"));
   }
   if ( referer.indexOf(':') >= 0 ){
      referer = referer.substr(0,referer.indexOf(":"));
   }
//   if ( referer && (referer != document.location.hostname) ){
   if ( referer && ( referer.indexOf("dahongbao.com") < 0 ) ){
      referer = escape(referer);
      if ( document.referrer.indexOf("?") >= 0 ){
         keyword = escape(top.document.referrer.substr(top.document.referrer.indexOf("?")+1));
      }
      else{
         keyword = 'Unknown';
      }
   }
   else{
      referer = 'Unknown';
      keyword  = 'Unknown';
   }
   if ( referer == 'Unknown' ){
      if ( getCookie("REFERER") ){
         referer = getCookie("REFERER");
         keyword = getCookie("KEYWORD");
      }
   }
   setCookie('REFERER',referer,"/",".dahongbao.com",new Date(today.getTime() + 604800000000));
   setCookie('KEYWORD',keyword,"/",".dahongbao.com",new Date(today.getTime() + 604800000000));

   if ( top.document.location.search.indexOf('source=') > 0 ){
      source = top.document.location.search.substr(top.document.location.search.indexOf('source=')+7);
      if ( source.indexOf('&') > 0 ){
         source = source.substr(0,source.indexOf('&'));
      }
      source = escape(source);
   }
   else{
      source = 'Unknown';
   }
   if ( source == 'Unknown' ){
      if ( getCookie('SOURCE') ){
         source = getCookie('SOURCE');
      }
   }
   setCookie('SOURCE',source,"/",".dahongbao.com",new Date(today.getTime() + 604800000000));
   document.write('<img width="1" height="1" src="http://' + window.location.hostname + '/stat.php?newvisit=' + newsess + '&c=' + category + '&m=' + merchant + '&p=' + coupon + '&referer=' + referer + '&keyword=' + keyword + '&source=' + source + '&is_frame=' + is_frame + '">');
}

// @author Kaluk Lee
// @note This function would try to find the sourcegroup request parameter
// @note This function use 30 days for the life span of a cookie.
function make_stat(category,merchant,coupon,is_frame){
   var today   = new Date();
   var newsess = 0;

   var CSession = getCookie("SESSION");
   if ( !CSession ){
      newsess = 1;
      setCookie("SESSION","1","/",".dahongbao.com");
   }

   var referer  = top.document.referrer;
   var keyword  = 'Unknown';
   var isSticky = 0;

   if( referer ) {

       if ( referer.indexOf('//') >= 0 ){
          referer = referer.substr(referer.indexOf("//")+2);
       }
       if ( referer.indexOf('/') >= 0 ){
          referer = referer.substr(0,referer.indexOf("/"));
       }
       if ( referer.indexOf('?') >= 0 ){
          referer = referer.substr(0,referer.indexOf("?"));
       }
       if ( referer.indexOf(':') >= 0 ){
          referer = referer.substr(0,referer.indexOf(":"));
       }

       if ( referer && ( referer.indexOf("dahongbao.com") < 0 ) ){
          referer = escape(referer);

          if ( document.referrer.indexOf("?") >= 0 ){
             keyword = escape(top.document.referrer.substr(top.document.referrer.indexOf("?")+1));
         }
       }
       else {
           referer = 'Unknown';
       }
   }
   else {
       referer = 'Unknown';
   }

   var prevRef = "";
   var prevKey = "";

   if( referer == 'Unknown' ) {
       prevRef = getCookie("REFERER");
       if( prevRef != null && prevRef != 'Unknown' ) {
           referer = prevRef;
           isSticky = 1;
       }

       prevKey = getCookie("KEYWORD");
       if( prevKey != null ) {
           keyword  = prevKey;
       }
   }

   // cookie will expire in 30 days
   setCookie('REFERER',referer,"/",".dahongbao.com",new Date(today.getTime() + 2592000000));
   setCookie('KEYWORD',keyword,"/",".dahongbao.com",new Date(today.getTime() + 2592000000));

   var source = 'Unknown';
   source = set_source();

   document.write('<img width="1" height="1" src="http://' + window.location.hostname + '/stat.php?newvisit=' + newsess + '&c=' + category + '&m=' + merchant + '&p=' + coupon + '&referer=' + referer + '&keyword=' + keyword + '&source=' + source + '&is_frame=' + is_frame + '">');
}

/*
 This function set the cookie for the source and sourcegroup.
*/
function set_source() {
   var today       = new Date();
   var source      = 'Unknown';
   var sourcegroup = 'Unknown';

   source = find_source();
   sourcegroup = find_sourcegroup();

   setCookie('SOURCE',source,"/",".dahongbao.com",new Date(today.getTime() + 2592000000));
   setCookie('SOURCEGROUP',sourcegroup,"/",".dahongbao.com",new Date(today.getTime() + 2592000000));
   return source;

}


/*
 This function is used to generate frameset for merchant index page.
 Acutally, this function targets the src for the merchant page.
 Source information is required for user activities tracking.
 The UserTracking.php included inside the redir.php looks for
 the source information.
 */

function make_merchantframe(page, redir, frm) {
    var source = find_source();

    if( redir != '' && frm == 1 ) {
        document.write('<frameset rows="*,0,0" frameborder="NO" border="0" framespacing="0" >');
    }
    else {
        document.write('<frameset rows="*,0" frameborder="NO" border="0" framespacing="0">');
    }
    document.write('<frame border="0" src="' + page + '">');
    document.write('<frame name="dropdown" border="0" scrolling="NO" noresize src="/hot_coupons.php">');
    if( redir != '' && frm == 1 ) {
        document.write('<frame name="merchant" border="0" scrolling="NO" noresize src="' + redir + '&source=' + source + '">');
    }
    document.write('</frameset>');
}

function make_frames(page, merchant, frm, m_id, m_name) {
    var source = find_source();
    if (m_name){
      window.status='商家 '+m_name+' 的优惠 -- 每天更新！';
    }
    else{
      window.status='欢迎来到大红包购物！';
    }
    if( merchant != '') {
       document.write('<frameset rows="*,0" frameborder="NO" border="0" framespacing="0"  onunload="popup('+m_id +')">');
    }
    else{
       document.write('<frameset rows="*,0" frameborder="NO" border="0" framespacing="0">');
    }
    document.write('<frame border="0" src="' + page + '">');
    if( merchant != '' && frm == 1) {
        document.write('<frame name="merchant" border="0" scrolling="NO" noresize src="/hidden.php?source=' + source + '&' + merchant + '">');
    }
    else {
        document.write('<frame name="merchant" border="0" scrolling="NO" noresize src="/hidden.php?source=' + source + '">');
    }
    document.write('</frameset>');
}





/*
  This function is factored from the make_stat function.
  The main purpose of this function is locating source
  information from the incoming request URL. If it could
  not locate the source, then it would try to get it
  from exisiting Source cookie.
*/
function find_source() {
   var source = 'Unknown';

   if ( top.document.location.search.indexOf('source=') > 0 ){
      source = top.document.location.search.substr(top.document.location.search.indexOf('source=')+7);
      if ( source.indexOf('&') > 0 ){
         source = source.substr(0,source.indexOf('&'));
      }
      source = escape(source);
   }
   else{
      source = 'Unknown';
   }

   if ( source == 'Unknown' ){

      var regExpObj1  = /^\/([^\/]+)\/([^\/]+)\/$/;
      var regExpObj2  = /^\/([^\/]+)\/([^\/]+)\/([^\/]+)\/$/;
      var regExpObj3  = /^\/([^\/]+)\/([^\/]+)\/([^\/]+)\/([^\/]+)\/$/;

      var url  = top.document.location.pathname;
      if ( regExpObj1.exec(url) ){
         source   = regExpObj1.exec(url)[2];
      }
      if ( regExpObj2.exec(url) ){
         source   = regExpObj2.exec(url)[3];
      }

      if ( regExpObj3.exec(url) ){
         source   = regExpObj3.exec(url)[4];
      }
   }

   if ( source == 'Unknown' ){
      if ( getCookie('SOURCE') ){
         source = getCookie('SOURCE');
      }
   }
   return source;
}

function find_sourcegroup() {
   var sourcegroup = 'Unknown';

   if ( top.document.location.search.indexOf('source=') > 0 ){
      source = top.document.location.search.substr(top.document.location.search.indexOf('source=')+7);
      if ( source.indexOf('&') > 0 ){
         source = source.substr(0,source.indexOf('&'));
      }

      if( source.indexOf("_") > 0 ) {
         sourcegroup = source.substr(0, source.indexOf("_"));
      }
      else {
         sourcegroup = source;
      }

      sourcegroup = escape(sourcegroup);
   }
   else{
      sourcegroup = 'Unknown';
   }

   if ( sourcegroup == 'Unknown' ){

      var regExpObj1  = /^\/([^\/]+)\/([^\/]+)\/$/;
      var regExpObj2  = /^\/([^\/]+)\/([^\/]+)\/([^\/]+)\/$/;
      var regExpObj3  = /^\/([^\/]+)\/([^\/]+)\/([^\/]+)\/([^\/]+)\/$/;

      var url  = top.document.location.pathname;
      if ( regExpObj1.exec(url) ){
         sourcegroup = regExpObj1.exec(url)[1];
      }
      if ( regExpObj2.exec(url) ){
         sourcegroup = regExpObj2.exec(url)[2];
      }

      if ( regExpObj3.exec(url) ){
         sourcegroup = regExpObj3.exec(url)[3];
      }
   }

   if ( sourcegroup == 'Unknown' ){
      if ( getCookie('SOURCEGROUP') ){
         sourcegroup = getCookie('SOURCEGROUP');
      }
   }
   return sourcegroup;
}


// @author Kaluk Lee
// return value of a parameter in the URL.
function getSearchInfo(key) {
   var value = 'unset';

   if ( top.document.location.search.indexOf(key) > 0 ) {
      value = top.document.location.search.substr(top.document.location.search.indexOf(key)+key.length);
      if ( value.indexOf('=') > 0 ) {
          // I believe we need to handle the case the char & is used for the source.
          // For example, overture_flowers_&_gifts

          value = value.substr(0, value.indexOf('='));
          value = value.substr(0, value.lastIndexOf('&'));
      }
      value = escape(value);
   }

   return value;
}

function addm(form_name,listto,listfrom){
   var tmpValue;
   var selValue;
   var selText;
   var fromlen;
   var selitem;
   var len;
   eval("fromlen=window.document.forms['" + form_name + "']." + listfrom + ".length");
   for (var j=0; j<fromlen; j++){
      eval("selitem=window.document.forms['" + form_name + "']." + listfrom + "[" + j + "].selected");
      if (selitem){
         selIndex = j;
         eval("selValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "].value");
         eval("selText=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "].text");
         eval("len=window.document.forms['" + form_name + "']." + listto + ".length");
         for (var i=0; i < len; i++){
            eval("tmpValue=window.document.forms['" + form_name + "']." + listto + ".options[" + i + "].value");
            if ( tmpValue == selValue ){
               break;
            }
         }
         if ( tmpValue != selValue ){
            var opt = new Option(selText,selValue);
            eval("window.document.forms['" + form_name + "']." + listto + ".options[" + "window.document.forms['" + form_name + "']." + listto + ".length]=opt");
         }
      }
   }
}
function add(form_name,listto,listfrom){
   eval("selIndex=window.document.forms['" + form_name + "']." + listfrom + ".selectedIndex");
   if ( selIndex < 0 ) return;
   eval("selValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "].value");
   eval("selText=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "].text");
   eval("len=window.document.forms['" + form_name + "']." + listto + ".length");
   for (i=0; i < len; i++){
      eval("tmpValue=window.document.forms['" + form_name + "']." + listto + ".options[" + i + "].value");
      if ( tmpValue == selValue ) return;
   }
   var opt = new Option(selText,selValue);
   eval("window.document.forms['" + form_name + "']." + listto + ".options[" + "window.document.forms['" + form_name + "']." + listto + ".length]=opt");
}
function remove(form_name,listfrom){
   eval("selIndex=window.document.forms['" + form_name + "']." + listfrom + ".selectedIndex");
   if ( selIndex < 0 ) return;
   eval("selIndex=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "] = null");
}
function removem(form_name,listfrom){
   var arrstr = "0";
   eval("fromlen=window.document.forms['" + form_name + "']." + listfrom + ".length");
   for (var j=0; j<fromlen; j++){
      eval("selitem=window.document.forms['" + form_name + "']." + listfrom + "[" + j + "].selected");
      if (selitem){
         selIndex = j;
         eval("selValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + selIndex + "].value");
         arrstr = arrstr + ',' + selValue;
      }
   }
   eval("selItem=new Array(" + arrstr + ")");
   for (var j=0; j<selItem.length; j++){
      eval("len=window.document.forms['" + form_name + "']." + listfrom + ".length");
      for (var i=0; i < len; i++){
         eval("tmpValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + i + "].value");
         if ( selItem[j] == tmpValue ){
            eval("window.document.forms['" + form_name + "']." + listfrom + ".options[" + i + "] = null");
            break;
         }
      }
   }
}
function list2str(form_name,listfrom,strto){
   var result = "";
   var tmpValue = "";
   eval("len=window.document.forms['" + form_name + "']." + listfrom + ".length");
   for (i=0; i < len; i++){
      eval("tmpValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + i + "].value");
      result = result + tmpValue + ";";
   }
   eval("window.document.forms['" + form_name + "']." + strto + ".value='" + result + "'");
}
function sbmit(){
   if ( window.document.form_news_letter ){
      if (check_mail(window.document.form_news_letter.email)){
         window.document.form_news_letter.submit();
      }
   }
   else{
      if (check_mail(top.frames[0].document.form_news_letter.email)){
         top.frames[0].document.form_news_letter.submit();
      }
   }
}
function mouseover(rt,id){
   if ( !isLoadedPage ) return;
   if ( !self.document.all ) return;
   self.document.all['cat1_' + id].background = rt + 'images/category_background_active.gif';
   self.document.all['cat2_' + id].background = rt + 'images/category_background_active.gif';
   self.document.all['cat3_' + id].background = rt + 'images/category_background_active.gif';
   self.document.all['cat_arrow_' + id].src   = rt + 'images/category_arrow.gif';
}
function mouseout(rt,id,status){
   if ( !isLoadedPage ) return;
   if ( !self.document.all ) return;
   if ( status != 'on' ){
      self.document.all['cat1_' + id].background = rt + 'images/category_background.gif';
      self.document.all['cat2_' + id].background = rt + 'images/category_background.gif';
      self.document.all['cat3_' + id].background = rt + 'images/category_background.gif';
      self.document.all['cat_arrow_' + id].src   = rt + 'images/category_background.gif';
   }
}
function mouseclick(rt,url,id){
   if ( !isLoadedPage ) return;
   if ( !self.document.all ) return;
   mouseover(rt,id);
   top.document.location.href=url;
}
function searchMenu(href,opt){
   new_url = '';
   href1 = href.substr(0,href.indexOf(".html"));
   if ( href1 != '' ){
      lschar = href1.substr(href1.length-2);
      new_url = href1;
      if ( lschar == "_m" || lschar == "_p" ){
         new_url = href1.substr(0,href1.length-2);;
      }
      new_url = new_url+opt+'.html';
   }
   else{
      new_url = href + '&ST=' + opt;
   }
   return new_url;
}

function afp_stat() {

    // using function written by Valery Zavolodko
    // for dahongbao.com/jscript/cookie.js)
    var afpie = getCookie("AFP");

    var afp_statscript = "http://affiliates.dahongbao.com/afp_altclick.php";

    var url = top.document.location.search;
    var src = "";
    var params = "";

    if( afpie != "1" ) {
        // if cookie "AFP" is absent, create new cookie for AFP
        // don't set expiration time to remove cookie after browser window is closed
        setCookie("AFP", "1", "/", ".dahongbao.com");

        if (url.indexOf('crid=') > 0 && url.indexOf('afid=') > 0) {
            params = url.substring(url.indexOf('crid='), url.length);
            src = afp_statscript + '?' + params;
            document.write('<img src="'+ src + '" border="0" alt="" width="1" height="1">');
            //  = params.split();
             crid = ""; afid = "";

            pairs = params.split("&");

            cridpair = pairs[0];
            pair = cridpair.split("=");
            crid = pair[1];

            afidpair = pairs[1];
            pair = afidpair.split("=");
            afid = pair[1];

            setCookie("CRID", crid, "/", ".dahongbao.com");
            setCookie("AFID", afid, "/", ".dahongbao.com");
        }
    }
}
