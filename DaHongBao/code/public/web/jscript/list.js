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
