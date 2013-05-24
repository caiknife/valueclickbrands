function make_stat(category,merchant,coupon){
   var today = new Date();
   var newsess = 0;
   var CSession = getCookie("SESSION");
   if ( !CSession ){
      newsess = 1;
      setCookie("SESSION","1");
   }

   var referer;
   var keyword;
   var source;
   referer = document.referrer;
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
   if ( referer && (referer != document.location.hostname) ){
      referer = escape(referer);
      if ( document.referrer.indexOf("?") >= 0 ){
         keyword = escape(document.referrer.substr(document.referrer.indexOf("?")+1));
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
   setCookie('REFERER',referer,new Date(today.getTime() + 604800000000));
   setCookie('KEYWORD',keyword,new Date(today.getTime() + 604800000000));
   if ( document.location.search.indexOf('source=') > 0 ){
      source = document.location.search.substr(document.location.search.indexOf('source=')+7);
      if ( source.indexOf('&') > 0 ){
         source = source.substr(0,source.indexOf('&'));
      }
      source = escape(source);
   }
   else{
      source = 'Unknown';
   }
   if ( source == 'Unknown' ){
      if ( getCookie("SOURCE") ){
         source = getCookie("SOURCE");
      }
   }
   setCookie('SOURCE',source,new Date(today.getTime() + 604800000000));
   document.write('<img width="1" height="1" src="http://' + window.location.hostname + '/stat.php?newvisit=' + newsess + '&c=' + category + '&m=' + merchant + '&p=' + coupon + '&referer=' + referer + '&keyword=' + keyword + '&source=' + source + '">');
}