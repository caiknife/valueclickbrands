function show_restriction(text){
   if ( text == "" ){
      text = 'û���κ�����';
   }
   rwin = window.open('','','scrolbars=no, resizable=no, width=250, height=150');
   rwin.document.write('<html><head><title>�Ż�ȯ���÷�Χ</title></head><body bgcolor="#6666CC"><table height="100%" width="100%"><tr><td style="font-family:Arial,Helvetica,sans-serif; font-size:9pt;color:#FFFFFF;">' + text + '</td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a style="font-family:Arial,Helvetica,sans-serif; font-size:9pt; color:#000000;" href="JavaScript:self.close();">�ر�</a></td></tr></table></body></html>');
}