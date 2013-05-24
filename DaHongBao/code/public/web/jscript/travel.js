$(document).ready(function() {
	$("#changebg a").click(function() {
		addTabClass("search_content");
		if ($(this).text() == "机票查询"){
			$("#changebg").removeClass().addClass("ticket");
		}
		else if ($(this).text() == "酒店查询") {
			$("#changebg").removeClass().addClass("hotel");
		}
		else {
			$("#changebg").removeClass().addClass("line");
		}
		var t = $(this).attr('tag');
		$("."+$(this).attr('tag')).removeClass("disn");
	});

	$("#destination").click(function() {
		$("#citylist").show();
	});

	tourToDestination();

	$(".moredate").click(function() {
		$("#moredate").fadeIn('fast');
		$("#lessdate").fadeOut('fast');
		$(this).hide();
		return false;
	});

	$("#hot_hotel a").click(function() {
		if ($(this).text() == "更多"){
			return true;
		}
		 //去掉所有的active
		 romoveTabClass("hot_hotel", "active");
		 $(this).addClass("active");
		 var length = $("#hotel_content").children().length;
		 for (var i = 0; i < length; i++) {
			 if ($("#hotel_content").children().eq(i).attr('tag') == $(this).attr('tag')) {
				 $("#hotel_content").children().eq(i).removeClass("disn");
			 }
			 else {
				$("#hotel_content").children().eq(i).addClass("disn");
			 }
		 }
		 $(".clr").show();
		 return false;
	});

	$("#hot_flight a").click(function() {
		if ($(this).text() == "更多"){
			return true;
		}
		 //去掉所有的active
		 romoveTabClass("hot_flight", "active");
		 $(this).addClass("active");
		 var length = $("#flight_content").children().length;
		 for (var i = 0; i < length; i++) {
			 if ($("#flight_content").children().eq(i).attr('tag') == $(this).attr('tag')) {
				 $("#flight_content").children().eq(i).removeClass("disn");
			 }
			 else {
				$("#flight_content").children().eq(i).addClass("disn");
			 }
		 }
		 $(".clr").show();
		 return false;
	});
	changeDepartCity();

	$("#flightFromCity").click(function() {
		$("#flightCitylist").show();
	});
	$("#flightCitylist div>a").click(function() {
			$("#flightFromCity").val($(this).text());
			$("#flightCitylist").hide();
			return false;
	});

})

function addTabClass(obj, className) {
	className = (typeof(className) == "undefined" ? "disn":className);
	var object = $("#"+obj);
	var length = object.children().length;
	for (var i = 0; i < length; i++) {
		object.children().eq(i).addClass(className);
	}
}

function romoveTabClass(obj, className) {
		className = (typeof(className) == "undefined" ? "disn":className);
		var object = $("#"+obj);
		var length = object.children().length;
		for (var i = 0; i < length; i++) {
			object.children().eq(i).children().removeClass(className);
		}
}

function checkHotel() {
	if ($("#hotelCity").val() == "") {
		alert("请输入城市名称");
		return false;
	}
	return true;
}

function hotel_compare(obj) {
	$("#"+obj).toggleClass("disn");
}

function changeDepartCity() {
	var city = $(".departCity option:selected").get(0).text;
	if (city) {
		var url = "/async_travelHotRegion.php";
		$("#hotTourRegion").html("<img src='/images/travel/icon_loading.gif'>");
		$.ajax({
			url : url,
			type : "get",
			data : {"departCity": city } ,
			success : function(msg) {
				if(msg) {
					$("#hotTourRegion").html(msg);
					tourToDestination();
				}
			}
		});
	}
	return false;
}

	function tourToDestination() {
		$("#citylist div>a").click(function() {
			$("#destination").val($(this).text());
			$("#citylist").hide();
			return false;
		});
	}

	function checkFlights() {
		return true;
	}

	var flight_price = new Array();
	var flagShow = 0;
	searchTicketResult  = function (searchResult) {
		var addMerchant = 0;
		if (typeof(searchResult['tickets']) != "undefined"){
			if (flagShow == 0) {
				$("#searchResult").show();
				$("#flightLoading").hide();
				flagShow = 1;
			}
			for (var key in searchResult['tickets']){
				addMerchant = 0;
				var flightTmp = searchResult['tickets'][key];
				if ($("#"+flightTmp["flightNo"]).size() < 1) { //add new flights
					var flightsContent = '<div id="'+flightTmp["flightNo"]+'" onclick="clickFlights(\''+flightTmp["flightNo"]+'\');" class="fb_div"><ul><li class="airlogo"><img align="absmiddle" src="/images/travel/airwayslogo/'+flightTmp["flightNo"].substr(0,2).toLowerCase()+'.gif" /></li><li class="company"><span class="c_company">'+flightTmp['airlines']+'航空公司<br/><b class="fn">'+flightTmp["flightNo"]+'</b></span></li><li class="title_airname">'+flightTmp['startAirdrome']+'<br/>'+flightTmp['endAirdrome']+'</li><li class="air_time">'+flightTmp['startTime']+'<br><b>'+flightTmp['endTime']+'</b></li><li class="flight_plane">'+flightTmp["plane"]+'</li><li class="flight_tax">￥'+flightTmp["tax"]+' </li><li class="flight_price">￥'+flightTmp['price']+'</li></ul><div class="clr"></div></div>';
					$(".air_list").append(flightsContent);
					addMerchant = 1;
				}
				//add new merchant
				var objMerchant = $("#"+flightTmp["flightNo"]).next();
				var discount = searchResult['tickets'][key]['discount']/10;
				if (discount == 10)  {
					discount = "";
				}
				else {
					discount = " ("+discount+"折)";
				}
				var insertContent = '<ul><li class="merchant_li"><a href="'+searchResult['clickURL']+'" rel="nofollow" target="_blank">'+searchResult['siteName']+'</a></li><li class="merchant_tel">电话：'+searchResult['phone']+'</li><li class="price_merchant">￥'+searchResult['tickets'][key]['price']+'<b>'+discount+'</b></li><li><a href="'+searchResult['clickURL']+'" rel="nofollow" target="_blank"><img src="/images/travel/btn_book.gif" align="absmiddle"></a></li></ul>';

				if (addMerchant == 1) {
					 $("#"+flightTmp["flightNo"]).after('<div class="other_price disn" tag="'+flightTmp["flightNo"]+'">'+insertContent+'</div>');
				}
				else if (objMerchant.attr("tag") == flightTmp["flightNo"]) {
						objMerchant.append(insertContent)	;
				}

				//update lower price
				if ((typeof(flight_price[flightTmp["flightNo"]]) == "undefined") || parseInt(flight_price[flightTmp["flightNo"]]) > parseInt(flightTmp['price'])) {
					flight_price[flightTmp["flightNo"]] = parseInt(flightTmp['price']);
					$("#"+flightTmp["flightNo"]+" .flight_price").html("￥"+flightTmp['price']);
				}
			}
		}
		else if (searchResult["status"] == "PARSE_FAIL" || typeof(searchResult['tickets']) == "undefined") {
			//查询失败
			$(".loadingPic").html("<b style='color:#ff0000;'>抱歉，没有找到您的相关信息。</b>");
			return false;
		}
	}

function clickFlights(obj) {
	$("#"+obj).next().toggleClass("disn");
}

function checkFlight() {
	if ($("#flightFromCity").val() == "") {
		alert("请输入出发城市名称");
		return false;
	}
	if ($("#flightToCity").val() == "") {
		alert("请输入目的地城市名称");
		return false;
	}
	return true;
}

function more_detail() {
	$(".hotel_intro").html($("#detail_more").html());
}

function fightSearch(fromCity, toCity, dateTime) {
	if (!(fromCity) || !(toCity) || !(dateTime)) {
		return false;
	}
	location.href= "/travel_search.php?fromCity="+fromCity+"&toCity="+toCity+"&FlightStartTime="+dateTime+"&flight=flight";
}

function checkTour() {
	if ($("#destination").val() == "") {
		alert("目的地城市不能为空");
		return false;
	}
}

