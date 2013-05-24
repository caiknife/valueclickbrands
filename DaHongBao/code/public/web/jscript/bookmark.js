function add_bookmark(){
   var url="http://www.dahongbao.com"
   var title="大红包 -- www.dahongbao.com"
   if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)){
   	if (confirm('添加“大红包”至您的收藏夹？')){
   	   window.external.AddFavorite (url,title);
      }
   }
   else{
      var msg = "对不起，收藏夹添加失败……";
      if (navigator.appName == "Netscape") msg += " (Press <CTRL+D>)";
      alert(msg);
   }
}
