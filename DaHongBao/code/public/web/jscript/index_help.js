/* code by 2007-04-15*/
// JavaScript Document
function show_it(i){
var i;
switch(i)
 {
 	case 1:
		document.getElementById("help_box1").style.display = "";			
		document.getElementById("help_box2").style.display = "none";
	break;	
	case 2:
		document.getElementById("help_box1").style.display = "none";		
		document.getElementById("help_box2").style.display = "";
	break;	
 }
}


function pic(v){
	if(v=='w'){
		document.getElementById("d1").style.display = "none";
		document.getElementById("d2").style.display = "";
		document.getElementById("d3").style.display = "none";

		document.getElementById("pic").src = "http://images.dahongbao.com/images/up/week.png";
		document.getElementById("tintro").innerHTML = "VIEW BY: <a href=\"javascript:pic('d')\"><span class=\"blue\">DAY</span></a> | <span class=\"bold\">WEEK</span> | <a href=\"javascript:pic('m')\"><span class=\"blue\">MONTH</span></a>";
	}

	if(v=='m'){
		document.getElementById("d1").style.display = "none";
		document.getElementById("d2").style.display = "none";
		document.getElementById("d3").style.display = "";
		document.getElementById("pic").src = "http://images.dahongbao.com/images/up/month.png";
		document.getElementById("tintro").innerHTML = "VIEW BY: <a href=\"javascript:pic('d')\"><span class=\"blue\">DAY</span></a> | <a href=\"javascript:pic('w')\"><span class=\"blue\">WEEK</span></a> | <span class=\"bold\">MONTH</span>";
	}

	if(v=='d'){
		document.getElementById("d1").style.display = "";
		document.getElementById("d3").style.display = "none";
		document.getElementById("d2").style.display = "none";
		document.getElementById("pic").src = "http://images.dahongbao.com/images/up/day.png";
		document.getElementById("tintro").innerHTML = "VIEW BY: <span class=\"bold\">DAY</span> | <a href=\"javascript:pic('w')\"><span class=\"blue\">WEEK</span></a> | <a href=\"javascript:pic('m')\"><span class=\"blue\">MONTH</span></a>";
	}

}

function checkgo(){
	if(document.form2.email.value=='')
	{
		alert('�������ʼ���ַ');
		return false;
	}else{
		alert('���������Ѿ��ɹ����ģ���л���Դ�����֧��');
		document.form2.submit();
	}
}