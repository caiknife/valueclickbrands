function add_bookmark(){
   var url="http://www.dahongbao.com"
   var title="���� -- www.dahongbao.com"
   if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)){
   	if (confirm('��ӡ��������������ղؼУ�')){
   	   window.external.AddFavorite (url,title);
      }
   }
   else{
      var msg = "�Բ����ղؼ����ʧ�ܡ���";
      if (navigator.appName == "Netscape") msg += " (Press <CTRL+D>)";
      alert(msg);
   }
}
