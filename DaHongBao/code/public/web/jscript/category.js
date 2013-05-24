	function trygo(value,cid,nameurl){
		if(location.href.split("/Ca")[1]!=="" && location.href.split("html")[1]==""){
				var html=location.href.split("/Ca")[1];
				var valueinfo = html.split(".html")[0];
				var nextvalue="";
				switch (valueinfo)
				{
					case "food":
						nextvalue=72;
						break;
					case "travel":
						nextvalue=70;
						break;
					case "gift":
						nextvalue=66;
						break;
					case "Cosmestics":
						nextvalue=68;
						break;
					case "video":
						nextvalue=76;
						break;
					case "Wireless":
						nextvalue=71;
						break;
					case "homegarden":
						nextvalue=97;
						break;
					case "pets":
						nextvalue=88;
						break;
					case "apparel":
						nextvalue=63;
						break;
					case "electronics":
						nextvalue=65;
						break;
					case "wedding":
						nextvalue=90;
						break;
					case "maketdetail":
						nextvalue=98;
						break;
					case "insurance":
						nextvalue=82;
						break;
					case "training":
						nextvalue=94;
						break;
					case "toys":
						nextvalue=86;
						break;
					case "books":
						nextvalue=62;
						break;
					case "cartoon":
						nextvalue=96;
						break;
					case "digital":
						nextvalue=77;
						break;
					case "Sports":
						nextvalue=75;
						break;
					case "auto":
						nextvalue=93;
						break;
					case "others":
						nextvalue=95;
						break;
				}
				var gourl = "Ca-"+nameurl+"--Ci-"+cid+"--City-" +value+".html";


		}else{
			var localurl = location.href.split("&cityselect")[0];
			var localurl = localurl.split("&pageid")[0];
			var gourl = localurl + "&cityselect=" +value;
			
		}
		location.href = gourl;
	}

	function trygosearch(value){
		var a = location.href.split(".com/")[1];
		var b = location.href.split("se")[0];
		var s = a.split("-")[1];
		var s = s.split("/")[0];
		//alert(s)
		var gourl = "se-"+s+"-1-"+value;
		//alert(gourl)
		location.href = b+gourl+"/";
		//alert(b)
	}