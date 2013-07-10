(function(w) {
	var d = document;
	var t = this;
	var wl = w.location;
	var _ie = !!(window.attachEvent && !window.opera);
	var _wk = /webkit\/(\d+)/i.test(navigator.userAgent) && (RegExp.$1 < 525);
	var _fn = [];
	var _run = function() {
		for ( var i = 0; i < _fn.length; i++)
			_fn[i]();
	};
	document.ready = function(_f) {
		if (!_ie && !_wk && document.addEventListener) {
			return document.addEventListener('DOMContentLoaded', _f, false);
		}
		if (_fn.push(_f) > 1) {
			return;
		}
		if (_ie) {
			(function() {
				try {
					document.documentElement.doScroll('left');
					_run();
				} catch (err) {
					setTimeout(arguments.callee, 0);
				}
			})();
		} else if (_wk) {
			t._st = setInterval(function() {
				if (/^(loaded|complete)$/.test(document.readyState)) {
					clearInterval(t._st);
					_run();
				}
			}, 0);
		}
	};
	w._Trk = {};
	t.sid = '';
	t.uid = '';
	t.rid = '';
	t.prid = '';
	t.c = [];
	t.pv = [];
	pageOpen = new Date();
	t.ct = pageOpen.getTime();
	t.vt = Math.round(t.ct / 1000);
	t._url = '';
	t.pre = 'mmc_';
	t.sep = '|*';
	t.tpv = true;
	t.count = 0;
	t.refer = d.referrer;
	t.ua = navigator.userAgent;
	t.uri = wl.pathname + wl.search + wl.hash;
	t.url = wl.protocol + "//" + wl.host + t.uri;
	w.onerror = function() {
		return false;
	};
	var B = {
		/**
		 * Function List mapping getCharacterSet -> cs getScreenResolution -> sr getScreenColors -> sc getTimezoneOffset -> to getCookieEnabled -> ce getJavaEnabled -> je getBrowserLanguage -> bl
		 * getFlashVersion -> fv
		 * 
		 */
		cs : function() {
			var cst = '';
			var _d = document;
			if (_d.characterSet) {
				cst = _d.characterSet;
			} else if (_d.charset) {
				cst = _d.charset;
			}
			return cst;
		},
		sr : function() {
			var srt = '-';
			if (self.screen) {
				srt = screen.width + "x" + screen.height;
			} else if (self.java) {
				var java = java.awt.Toolkit.getDefaultToolkit();
				var ss = java.getScreenSize();
				srt = ss.width + "x" + ss.height;
			}
			return srt;
		},

		sc : function() {
			return (self.screen) ? window.screen.colorDepth : '0';
		},

		to : function() {
			var n = new Date();
			var j1 = new Date(n.getFullYear(), 0, 1, 0, 0, 0, 0);
			var tmp = j1.toGMTString();
			var j2 = new Date(tmp.substring(0, tmp.lastIndexOf(" ") - 1));
			return (j1 - j2) / (1000 * 60 * 60);
		},

		ce : function() {
			return (navigator.cookieEnabled) ? 1 : 0;
		},

		je : function() {
			return navigator.javaEnabled() ? 1 : 0;
		},

		bl : function() {
			var bls = "-";
			var _n = navigator;
			if (_n.language) {
				bls = _n.language.toLowerCase();
			} else if (_n.browserLanguage) {
				bls = _n.browserLanguage.toLowerCase();
			}

			return bls;
		},

		fv : function() {
			var fvs = "-";
			var _n = navigator;
			if (_n.plugins && _n.plugins.length) {
				for ( var i = 0; i < _n.plugins.length; i++) {
					if (_n.plugins[i].name.indexOf('Shockwave Flash') != -1) {
						fvs = _n.plugins[i].description.split('Shockwave Flash ')[1];
						break;
					}
				}
			} else {
				var fos;
				try {
					fos = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
					fvs = fos.GetVariable("$version");
				} catch (e) {
				}

				if (fvs == "-") {
					try {
						fos = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
						fvs = "WIN 6,0,21,0";
						fos.AllowScriptAccess = "always";
						fvs = fos.GetVariable("$version");
					} catch (e) {
					}
				}

				if (fvs == "-") {
					try {
						fos = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
						fvs = fos.GetVariable("$version");
					} catch (e) {
					}
				}

				if (fvs != "-") {
					fvs = fvs.split(" ")[1].split(",");
					fvs = fvs[0] + "." + fvs[1] + " r" + fvs[2];
				}
			}
			return fvs;
		}
	};

	(function() {
		for ( var x in _t) {
			if (_t.hasOwnProperty(x)) {
				if (_t[x][0] == '_setIds') {
					t.uid = _t[x][1];
					t.sid = _t[x][2];
					t.rid = _t[x][3];
					t.prid = _t[x][4];
				}else if (_t[x][0] == '_setAccount') {
					t.acc = _t[x][1];
				}else if (_t[x][0] == '_setDomainName') {
					t._domain = _t[x][1];
				}else if (_t[x][0] == '_setUrl') {
					t._url = _t[x][1];
				}else if (_t[x][0] == '_trackPV') {
					t.tpv = _t[x][1];
				}
				if(_t[x][0].substr(0,4)=='_set'){
					delete _t[x];
				}
			}
		}
		t._domain = t._domain ? t._domain : d.domain;
		t.mm_trk = getCookie('t');
		t.mm_sid = getCookie('s');
		t.mm_a = getCookie('a');
		t.c['ru'] = 1;
		t.c['ld'] = 0;
		t.c['domain'] = t._domain;
		if (t.mm_sid == undefined || t.mm_sid == null) {
			if (t.mm_a == undefined || t.mm_a == null) {
				t.c['ru'] = 0;
				t.ed = t.ct + 1000 * 60 * 60 * 24 * 365 * 2;
				t.mm_a = t.ed + t.sep + t.uid + t.sep + vt + t.sep + vt + t.sep + vt + t.sep + 1;
			} else {
				t._a = t.mm_a.split(t.sep);
				t._a[3] = t._a[4];
				t._a[4] = t.vt;
				t._a[5] = parseInt(t._a[5]) + 1;
				t.ed = t._a[0];
				t.mm_a = t._a.join(t.sep);
			}
			t.c['ld'] = 1;
			t.mm_sid = uniqid();
			t.trk = [];
			t.trk[0] = t.prid
			t.trk[1] = t.rid;
			t.trk[2] = 1;
			t.trk[3] = t.refer;
			t.trk[4] = t.uri;
			t.trk[5] = t.ua;
			setCookie('s', t.mm_sid);
			setCookie('a', t.mm_a, t.ed);
		} else {
			t.trk = t.mm_trk.split(t.sep);
			t.trk[0] = t.prid
			t.trk[1] = t.rid;
			t.trk[2] = parseInt(t.trk[2]) + 1;
		}
		t.mm_trk = t.trk.join(t.sep);
		setCookie('t', t.mm_trk);
		t._a = t.mm_a.split(t.sep);
		t.c['uid'] = t._a[1];
		t.c['fvt'] = t._a[2];
		t.c['lvt'] = t._a[3];
		t.c['cvt'] = t._a[4];
		t.c['vts'] = t._a[5];
		t.c['prid'] = t.trk[0]
		t.c['rid'] = t.trk[1];
		t.c['pvs'] = t.trk[2];
		t.c['sid'] = t.sid;
		t.c['acc'] = t.acc;
		t.c['uri'] = t.uri;
		t.c['url'] = t.url;
		t.c['refer'] = t.refer;
		t.c['tk'] = fencode(t._a[4]);
		t.c['lt'] = 'ctrk'
		t.c['LL'] = [];
		if (t.tpv === true) {
			t.pv['bl'] = B.bl();
			t.pv['cs'] = B.cs();
			t.pv['tz'] = B.to();
			t.pv['sr'] = B.sr();
			t.pv['sc'] = B.sc();
			t.pv['je'] = B.je();
			t.pv['ce'] = B.ce();
			t.pv['fl'] = B.fv();
			t.pv['ua'] = t.ua;
			t.pv['vt'] = t.vt;
			t.pv['pv'] = t.tpv;
			if (document.getElementsByTagName('title')[0] != undefined) {
				t.pv['title'] = document.getElementsByTagName('title')[0].innerHTML;
			}
		}
		beat();
	})();

	function createXMLHttpRequest() {
		try {
			return new XMLHttpRequest();
		} catch (trymicrosoft) {
			try {
				return new ActiveXObject("Msxml2.XMLHTTP");
			} catch (othermicrosoft) {
				try {
					return new ActiveXObject("Microsoft.XMLHTTP");
				} catch (failed) {
					alert("XMLHttpRequest not supported");
				}
			}
		}
		return null;
	}

	function requestHttpObjText(url, objFunc, xmlbody) {
		var http_request = createXMLHttpRequest();
		http_request.onreadystatechange = objFunc;
		http_request.open("POST", url, true);
		http_request.setRequestHeader("cache-control", "no-cache");
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http_request.send(xmlbody);
		return http_request;
	}

	function uniqid(prefix) {
		var uid = new Date().getTime().toString(16);
		uid += Math.random().toString().substr(-8) + Math.random().toString().substr(-8) + Math.floor((1 + Math.random()) * Math.pow(16, (16 - uid.length))).toString(16).substr(1);
		return (prefix || '') + uid;
	}

	function setCookie(name, value, expires) {
		var exp = new Date;
		if (expires != undefined) {
			exp.setTime(expires);
		}
		document.cookie = t.pre + name + "=" + escape(value) + ((expires == undefined) ? '' : ("; expires=" + exp.toGMTString())) + "; path=/; domain=" + t._domain;
	}

	function delCookie(name) {
		name = t.pre + name;
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var cval = getCookie(name);
		document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString();
	}

	function getCookie(name) {
		name = t.pre + name;
		var re = new RegExp("(?:^| )" + name + "=([^;]*)", "i");
		var matches = document.cookie.match(re);
		return matches && matches.length == 2 ? unescape(matches[1]) : null;
	}

	function _request(_xml) {
		var _requesthttp = '';
		var processUpdatedUpLoadRequest = function() {
			if (_requesthttp.readyState == 4) {
				if (_requesthttp.status == 200) {
					return false;
				}
			}
		};
		_requesthttp = requestHttpObjText(t._url, processUpdatedUpLoadRequest, _xml);
	}

	function addEvent() {
		var event = '';
		this.trackEvent = true;

		for ( var i in arguments[0]) {
			event += '##' + arguments[0][i];
		}
		event = event.substr(2);
		this.events.push(event);
	}

	function loading(imgSrc) {
		var img = new Image(1, 1);
		img.src = imgSrc;
		img.onload = function() {
			img.onload = null;
		};
	}

	function in_array(val, arr) {
		for ( var i = 0, len = arr.length; i < len; i++) {
			if (arr[i] == val) {
				return true;
			}
		}
		return false;
	}

	function beat() {
		var xml = '';
		for ( var x in _t) {
			if (_t.hasOwnProperty(x)) {
				if (_t[x][0] != undefined) {
					t.c['LL'].push(_t[x].join(t.sep));
				}
			}
		}
		if (t.c['LL'].length > 0) {
			t.c['LL'] = t.c['LL'].join('{}');
			log();
		}
		if (t.count == 0) {
			for ( var i in t.pv) {
				if (check(t.pv[i])) {
					xml += i + '=' + encodeURIComponent(t.pv[i]) + '&';
				}
			}
			log(xml);
		}
		t.count += 1;
		setTimeout(beat, 1000);
	}

	_Trk.push = function(a) {
		t.c['LL'].push(a.join(t.sep));
		log();
	}

	function stayTime() {
		var pageClose = new Date();
		t.c['st'] = Math.round((pageClose.getTime() - pageOpen.getTime()) / 1000);
	}

	function check(a) {
		if (a === 0 || (a != '' && a != undefined)) {
			return true;
		}
		return false;
	}

	function log(xml) {
		if (xml == undefined) {
			var xml = '';
		}
		for ( var i in t.c) {
			if (check(t.c[i])) {
				xml += i + '=' + encodeURIComponent(t.c[i]) + '&';
			}
		}
		_t = [];
		t.c['LL'] = [];
		xml = xml.substring(0, xml.lastIndexOf('&'));
		_request(xml);
	}

	function fencode(s) {
		if (s != undefined) {
			var es = [], c = '', ec = '';
			s = s.split('');
			for ( var i = 0, length = s.length; i < length; i++) {
				c = s[i];
				ec = encodeURIComponent(c);
				if (ec == c) {
					ec = c.charCodeAt().toString(16);
					ec = ('00' + ec).slice(-2);
				}
				es.push(ec);
			}
			return es.join('').replace(/%/g, '').toUpperCase();
		}
		return null;
	}

	function genToken(str) {
		var chrsz = 8;
		var bin = Array();
		var mask = (1 << chrsz) - 1;
		str = str.replace(/\s/g, '') + this.acc;
		for ( var i = 0; i < str.length * chrsz; i += chrsz)
			bin[i >> 5] |= (str.charCodeAt(i / chrsz) & mask) << (i % 32);
		var rt = bin.join("0");
		return rt.charAt(0) + rt.charAt(2) + rt.charAt(5) + rt.charAt(8);
	}

	function pack() {
		var rstr = "";
		if (arguments[0].length > 0) {
			for ( var i = 0; i < arguments[0].length; i++) {
				rstr += "{r}" + arguments[0][i].join("{f}");
			}
			rstr = rstr.substr(3);
		}
		return rstr;
	}

	function send() {
		if (arguments.length > 1) {
			var rUri = this.rURI + '&t=' + this.genToken(arguments[1]) + '&a=' + arguments[0] + '&p=' + arguments[1];
			var img = new Image(1, 1);
			img.onload = function() {
				img.onload = null;
			};
			img.src = rUri;
		}
	}
})(window);