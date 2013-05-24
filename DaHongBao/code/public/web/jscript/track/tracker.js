function getCharacterSet()
{
    var characterSet = '';
    if (document.characterSet) {
        characterSet = document.characterSet;
    } else if (document.charset) {
        characterSet = document.charset;
    }

    return characterSet;
}

function getScreenResolution()
{
    var screenResolution = '-';
    if (self.screen) {
        screenResolution = screen.width + "x" + screen.height;
    } else if (self.java) {
        var java = java.awt.Toolkit.getDefaultToolkit();
        var screenSize = java.getScreenSize();
        screenResolution = screenSize.width + "x" + screenSize.height;
    }

    return screenResolution;
}

function getScreenColors()
{
    return (self.screen) ? window.screen.colorDepth : '0';
}

function getTimezoneOffset()
{
    var rightNow = new Date();
    var jan1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
    var temp = jan1.toGMTString();
    var jan2 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
    return (jan1 - jan2) / (1000 * 60 * 60);
}

function getCookieEnabled()
{
    return (navigator.cookieEnabled) ? 1 : 0;
}

function getJavaEnabled()
{
    return navigator.javaEnabled() ? 1 : 0;
}

function getBrowserLanguage()
{
    var browserLanguage = "-";

    if (navigator.language) {
        browserLanguage = navigator.language.toLowerCase();
    } else if (navigator.browserLanguage) {
        browserLanguage = navigator.browserLanguage.toLowerCase();
    }

    return browserLanguage;
}

function getFlashVersion()
{
    var _flashVersion = "-",_navigator = navigator;
    if (_navigator.plugins && _navigator.plugins.length) {
        for (var ii = 0; ii < _navigator.plugins.length; ii++) {
            if (_navigator.plugins[ii].name.indexOf('Shockwave Flash') != -1) {
                _flashVersion = _navigator.plugins[ii].description.split('Shockwave Flash ')[1];
                break;
            }
        }
    } else {
        var _flashObject;
        try {
            _flashObject = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
            _flashVersion = _flashObject.GetVariable("$version");
        } catch(e) {}

        if (_flashVersion == "-") {
            try {
                _flashObject = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
                _flashVersion = "WIN 6,0,21,0";
                _flashObject.AllowScriptAccess = "always";
                _flashVersion = _flashObject.GetVariable("$version");
            } catch(e) {}
        }

        if (_flashVersion == "-") {
            try {
                _flashObject = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
                _flashVersion = _flashObject.GetVariable("$version");
            } catch(e) {}
        }

        if (_flashVersion != "-") {
            _flashVersion = _flashVersion.split(" ")[1].split(",");
            _flashVersion = _flashVersion[0] + "." + _flashVersion[1] + " r" + _flashVersion[2];
        }
    }

    return _flashVersion;
}

function track(requestId, sessionId)
{
    var img = new Image(1, 1);
    img.src = '/track/scripts/async_tracker.php'
             + '?bl=' + getBrowserLanguage()
             + '&cs=' + getCharacterSet()
             + '&tz=' + getTimezoneOffset()
             + '&sr=' + getScreenResolution()
             + '&sc=' + getScreenColors()
             + '&je=' + getJavaEnabled()
             + '&ce=' + getCookieEnabled()
             + '&fl=' + getFlashVersion()
             + '&ri=' + requestId
             + '&si=' + sessionId
             + '&rt=' + ((new Date()).getTime() / 1000);
   img.onload = function() {
       img.onload = null;
   }
}
