function check_mail(vemail){
   if (vemail.value == ""){
      alert("������Email����"); 
      return false;
   }
   else{
      var gem = vemail.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
      if (!gem){
         alert("Email��ʽ��Ч����");
         vemail.focus();
         vemail.select();
         return false;
      }
      else{
         return true;
      }
   }
}