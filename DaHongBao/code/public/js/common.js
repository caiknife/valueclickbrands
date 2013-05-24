var base64DecodeChars = new Array(
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
-1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function redirectLink(url, link) {
    var _url = base64decode(url);
    link.setAttribute('href', _url);
    return false;
}

function modifyLink(url){
    url = base64decode(url);
    window.location.href=url;
    return false;
}

//获取鼠标坐标
function ___mouseCoords(ev) 
{ 
    if(ev.pageX || ev.pageY){ 
        return {
            x:ev.pageX, 
            y:ev.pageY
            }; 
    } 
    pageScroll = ___getPageScroll();
    return { 
        x:ev.clientX + pageScroll[0],
        y:ev.clientY + pageScroll[1]
    }; 
} 
//计算滑动块位置
function ___getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
        yScroll = self.pageYOffset;
        xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
        yScroll = document.documentElement.scrollTop;
        xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
        yScroll = document.body.scrollTop;
        xScroll = document.body.scrollLeft;	
    }
    arrayPageScroll = new Array(xScroll,yScroll);
    return arrayPageScroll;
};
//计算页面与窗口高宽
function ___getPageSize() {
    var xScroll, yScroll;
    if (window.innerHeight && window.scrollMaxY) {	
        xScroll = window.innerWidth + window.scrollMaxX;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }
    var windowWidth, windowHeight;
    if (self.innerHeight) {	// all except Explorer
        if(document.documentElement.clientWidth){
            windowWidth = document.documentElement.clientWidth; 
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }	
    // for small pages with total height less then height of the viewport
    if(yScroll < windowHeight){
        pageHeight = windowHeight;
    } else { 
        pageHeight = yScroll;
    }
    // for small pages with total width less then width of the viewport
    if(xScroll < windowWidth){	
        pageWidth = xScroll;		
    } else {
        pageWidth = windowWidth;
    }
    arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight);
    return arrayPageSize;
};

function base64decode(str) {
    var c1, c2, c3, c4;
    var i, len, out;

    len = str.length;
    i = 0;
    out = "";
    while(i < len) {
        /* c1 */
        do {
            c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        } while(i < len && c1 == -1);
        if(c1 == -1)
            break;

        /* c2 */
        do {
            c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        } while(i < len && c2 == -1);
        if(c2 == -1)
            break;
        out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
        
        /* c3 */
        do {
            c3 = str.charCodeAt(i++) & 0xff;
            if(c3 == 61)
                return out;
            c3 = base64DecodeChars[c3];
        } while(i < len && c3 == -1);
        if(c3 == -1)
            break;
        out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
        
        /* c4 */
        do {
            c4 = str.charCodeAt(i++) & 0xff;
            if(c4 == 61)
                return out;
            c4 = base64DecodeChars[c4];
        } while(i < len && c4 == -1);
        if(c4 == -1)
            break;
        out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
    }
    return out;
}

function glb_writeAds(position, type, outputType) {
	if (typeof(type) == 'undefined' || type == 'undefined') {
		type = '';
	}
	else {
		type = type + "_";
	}
	var adsWrap = document.getElementById("Ads_Wrap"+type+position);
	if (adsWrap) {
		adsWrap.style.display = "none";
	}
	try {
		var ads_position = eval("_ads_"+type+position);
		if (typeof(ads_position) != 'undefined') {
			//output type
			if (typeof(outputType) != 'undefined' && outputType == 'innerHTML') {
				adsWrap.innerHTML = ads_position;
			} else {
				document.write(ads_position);
			}
			if (adsWrap) {
				adsWrap.style.display = "block";
			}
		}
	}
	catch (e){
	}
}

function glb_ss(w,id) {
	window.status = w;
	return true;
}

function glb_cs() {
		window.status = "";
}

function glb_baidu_ss(w,obj) {
	window.status = w;
	if ($(this).parents(".baiduWrapClick") || $(this).parents(".baiduWrapClick_top")) {
		$(obj).addClass("ul_over");
	}
	return true;
}

function glb_baidu_cs(obj) {
	window.status = "";
	if ($(this).parents(".baiduWrapClick") || $(this).parents(".baiduWrapClick_top")) {
		$(obj).removeClass("ul_over");
	}
}

function glb_sogou_ss(w,obj) {
	window.status = w;
	if ($(this).parents(".baiduWrapClick") || $(this).parents(".baiduWrapClick_top")) {
		$(obj).addClass("ul_over");
	}
	return true;
}

function glb_sogou_cs(obj) {
	window.status = "";
	if ($(this).parents(".baiduWrapClick") || $(this).parents(".baiduWrapClick_top")) {
		$(obj).removeClass("ul_over");
	}
}

var running = false;//是否在运行中
var delay = 200;
var allCateTimer = null;

var redirect = encodeURI(window.location.href);

$(function(){
	var exp = new Date(); 
	exp.setTime(exp.getTime() + 5*60*60*1000);
	var cookieOpt = {expires: exp, path: '/', domain: '.dahongbao.com'};
	if (typeof(UserName) == 'undefined') {
		return false;
	}
	if(UserName && !$.cookie('UserName')) {
		$.cookie('UID', UID, cookieOpt);
		$.cookie('UserName', UserName, cookieOpt);
		$.cookie('AuthKey',  AuthKey, cookieOpt);
	}else if(!UserName && $.cookie('UserName')){
		$.cookie('UID', null, cookieOpt);
		$.cookie('UserName', null, cookieOpt);
		$.cookie('AuthKey',  null, cookieOpt);
	}
})

