function popdown(serverroot) {
   if (!getCookie("popdown")){
      setCookie("popdown","popdownvalue");
      window.open('http://' + document.location.hostname + '/popdown.html', 'POPDOWN', 'innerWidth=150, innerHeight=150, width=150, height=150, resizable=no, scrollbars=no, location=no');
      self.focus();
   }
}