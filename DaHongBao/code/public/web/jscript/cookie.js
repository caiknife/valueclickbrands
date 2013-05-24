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
function setCookie(name,value,expires,path,domain,secure){
   var expString  = ((expires == null) ? "" : ("; expires=" + expires.toGMTString()));
   var pathString  = ((path == null) ? "" : ("; path=" + path));
   var domainString = ((domain == null) ? "" : ("; domain=" + domain));
   var secureString = ((secure == true) ? "; secure" : "");
   document.cookie = name + "=" + escape(value) + expString + pathString + domainString + secureString;
}