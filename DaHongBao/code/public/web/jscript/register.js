
   function checkRegInfo(typ){
      if (typ == "chap"){
         if (document.form_register.txFirst.value == "" ||
             document.form_register.txLast.value == ""  ||
             document.form_register.txEmail.value == "" ||
             document.form_register.txPass.value != document.form_register.txPass_.value){
            alert("����д��������")
         }
         else {
            if (check_mail(document.form_register.txEmail))
               document.form_register.submit();
         }
      }
      else{
         if (document.form_register.txFirst.value == "" ||
             document.form_register.txLast.value == ""  ||
             document.form_register.txEmail.value == "" ||
             document.form_register.txPass.value == ""  ||
             document.form_register.txPass_.value == "" ||
             document.form_register.txPass.value != document.form_register.txPass_.value){
            alert("����д��������")
         }
         else {
            if (check_mail(document.form_register.txEmail))
               document.form_register.submit();
         }
      }
   }
