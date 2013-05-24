function user_set(form_name,user_action,id){
   window.document.forms[form_name].action.value = user_action;
   window.document.forms[form_name].id.value = id;
   window.document.forms[form_name].submit();
}
function confirm_delete(form_name,user_action,id,name,type){
   if ( confirm('Are you sure to delete ' + type + ' "' + name + '"?') ){
      user_set(form_name,user_action,id);
   }
}
function mass_delete(form_name,user_action,type){
   if ( confirm('Are you sure to delete all selected ' + type + '?') ){
      user_set(form_name,user_action);
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
function list2str(form_name,listfrom,strto){
   result = "";
   eval("len=window.document.forms['" + form_name + "']." + listfrom + ".length");
   for (i=0; i < len; i++){
      eval("tmpValue=window.document.forms['" + form_name + "']." + listfrom + ".options[" + i + "].value");
      result = result + tmpValue + ";";
   }
   eval("window.document.forms['" + form_name + "']." + strto + ".value='" + result + "'"); 
}
function gopage(form_name,pagelist,pagenum){
   eval("pagecount=window.document.forms['" + form_name + "']." + pagelist + ".length");
   if (pagenum > 0 && pagenum<=pagecount){
      window.document.forms[form_name].page.value = pagenum;
      window.document.forms[form_name].action.value = 'page';
      window.document.forms[form_name].submit();
   }
}
function putvalue(form_name,listfrom,inputto){
   eval("window.document.forms['" + form_name + "']." + inputto + ".value=window.document.forms['" + form_name + "']." + listfrom + ".options[window.document.forms['" + form_name + "']." + listfrom + ".selectedIndex].value");
}
