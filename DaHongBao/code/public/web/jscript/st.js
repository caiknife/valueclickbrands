var glb_cancelSt = false;
var glb_loadPosSt = '';
glb_docObj=document.documentElement!=undefined&&document.documentElement.clientHeight!=0?document.documentElement:document.body;
glb_d = new Date();
glb_StartTime = glb_d.getTime();
if(!window.attachEvent && window.addEventListener)
{
  window.attachEvent = HTMLElement.prototype.attachEvent=
  document.attachEvent = function(en, func, cancelBubble)
  {
    var cb = cancelBubble ? true : false;
    this.addEventListener(en.toLowerCase().substr(2), func, cb);
  };
  window.detachEvent = HTMLElement.prototype.detachEvent=
  document.detachEvent = function(en, func, cancelBubble)
  {
    var cb = cancelBubble ? true : false;
    this.removeEventListener(en.toLowerCase().substr(2), func, cb);
  };
}
//document.onload = doLoadPostSt;
window.attachEvent("onload", glb_doLoadPostSt);
document.attachEvent("onmousemove", glb_doSt);
//document.onmousemove  = doSt;
function glb_doSt(e) {
	if(glb_cancelSt) {
		return;
	}
	glb_cancelSt = true;
	e = e ? e : window.event;
	document.detachEvent("onmousemove", glb_doSt);
	var req;
	var w=glb_docObj.clientWidth!=undefined?glb_docObj.clientWidth:window.innerWidth;
	var h=glb_docObj.clientHeight!=undefined?glb_docObj.clientHeight:window.innerHeight;
	var scrollx=window.pageXOffset==undefined?glb_docObj.scrollLeft:window.pageXOffset;
    var scrolly=window.pageYOffset==undefined?glb_docObj.scrollTop:window.pageYOffset;
	x = e.clientX + scrollx;
	y = e.clientY + scrolly;
	movePos = x+"|"+y+"|"+e.screenX+"|"+e.screenY+"|"+w+"|"+h;
	screenSize = window.screen.width+"|"+window.screen.height;
	var d2 = new Date();
	actionTime = d2.getTime() - glb_StartTime;
	url = "/stp.php?loadPos="+glb_loadPosSt+"&movePos="+
		  movePos+"&actionTime="+actionTime+"&screenSize="+screenSize+"&url="+window.location;
	if(window.XMLHttpRequest){
		req = new XMLHttpRequest();
	}else if(window.ActiveXObject){
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(req) {
	    req.open("GET", url, true);
	    req.send(null);
	}
	
}

function glb_doLoadPostSt() {	
	if(window.event) {
		var w=glb_docObj.clientWidth!=undefined?glb_docObj.clientWidth:window.innerWidth;
		var h=glb_docObj.clientHeight!=undefined?glb_docObj.clientHeight:window.innerHeight;
		var scrollx=window.pageXOffset==undefined?glb_docObj.scrollLeft:window.pageXOffset;
		var scrolly=window.pageYOffset==undefined?glb_docObj.scrollTop:window.pageYOffset;
		x = window.event.clientX + scrollx;
		y = window.event.clientY + scrolly;
		glb_loadPosSt = x+"|"+y+"|"+window.event.screenX+"|"+window.event.screenY+"|"+w+"|"+h;
		//alert(loadPosSt);
	}
}

function glb_baidu_ss(w,obj) {
	window.status = w;
	$(obj).className = $(obj).className + ' selected';
	return true;
}

function glb_baidu_cs(obj) {
	window.status = "";
	$(obj).className = $(obj).className.replace(' selected', '');
}

var glb_syncRequestAdsUrl = '';
var glb_cancelSRequest = false;

function glb_SyncRequestAds(url) {
    glb_syncRequestAdsUrl = url;
    document.attachEvent("onmousemove", glb_sendRequest);
}

//request google ads url
function glb_sendRequest() {
    if (glb_cancelSRequest) {
        return;
    }
    glb_cancelSRequest = true;
    if (glb_syncRequestAdsUrl == '') {
        return false;
    }
    document.attachEvent("onmousemove", glb_sendRequest);
    //create script html
    var js = document.createElement("script");
    js.setAttribute('type', 'text/javascript');
    js.setAttribute('src', glb_syncRequestAdsUrl+"&time="+Math.random()*1000);
    js.setAttribute('id', 'MzJsonElement');
    if (js.addEventListener) {
        js.onload = function() {
            window.jsHandler();
    };
    } else if (js.attachEvent){
        js.attachEvent("onreadystatechange", function() {
            js.callbackIE();
        });
    }
    document.getElementsByTagName("head")[0].appendChild(js);
    js.callbackIE = function() {
        var target = window.event.srcElement;
        if (target.readyState == "loaded") {
            window.jsHandler();
        }
    };

    //view html onload succcess 
    window.jsHandler = function () {
        try {
            var position = 1;
            var value = eval('requestGoogle_'+position);
			if(value == '' || typeof (value) != 'string') {
				return false;
			}
            do {
                var googleObj = $('requestGoogle_'+position);
                if(googleObj && googleObj.innerHTML == '') {
                    googleObj.innerHTML = value;
                    googleObj.style.display = 'block';
                }
                position++;
                value = eval('requestGoogle_'+position);
            }
            while (value && typeof (value) == 'string');
        } catch (e){
        }
    }
}