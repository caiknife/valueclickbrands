var glbCellNumber = "";
function checkCellNumber(formsubmit,amountgo){
		var amountgo = amountgo;
		var formsubmit=formsubmit;
		var reg = /^1[3,5][0-9]{9,9}$/;
		var mobile = document.getElementById("inputId").value;

		if(amountgo!=1){
			if(mobile.length<11){
				return false;
			}
		}

		if(!reg.test(mobile)){
			document.getElementById('phonealert').innerHTML = "请输入正确的手机号码";
			return false;
		}else{
			
			var a = document.FORM.amount;
			for(i=0;i<a.length;i++){
				if(document.FORM.amount[i].checked==true){
					var am = document.FORM.amount[i].value;
				}
			}

			var numberam = mobile+am;
			

			if(glbCellNumber!=numberam){
				glbCellNumber = numberam;
				askFavAmount(mobile,am);
				
			}else{
				
			}

			if(formsubmit == "formsubmit"){
				document.FORM.submit();
			}
	
			return false;
		}

}

function askFavAmount(phone,amount){
	document.getElementById('phonealert').innerHTML = "优惠价格查询中...";
	var url = '/async_askFavAmount.php'
	var pars = 'mphone=' + phone +'&amount=' + amount;
	var myAjax = new Ajax.Request(
	url,
			{
			method: 'get',
			parameters: pars,
			onComplete: showResponse
			});
}
		function showResponse(originalRequest)
		{
			var now = originalRequest.responseText;
			if(now==""){
				document.getElementById('phonealert').innerHTML = "全价购买";
			}else{
				document.getElementById('phonealert').innerHTML = "最新优惠价 <B>"+ now + "</B> 元";
			}
		}