<?php
$_GET_ORIG  = $_GET;
$_GET = Tracking_Uri::extract($_SERVER['REQUEST_URI']);

/* for sponsor */

/* channel id */
if (isset($_GET[Tracking_Uri::CHANNEL_ID])){
    $_GET['chid'] = $_GET[Tracking_Uri::CHANNEL_ID];
}

/* channel tag */
if (isset($_GET[Tracking_Uri::CHANNEL_TAG])){
    $_GET['sltag'] = base64_encode($_GET[Tracking_Uri::CHANNEL_TAG]);
}

/* destined url */
if (isset($_GET[Tracking_Uri::DESTINED_URL])){
    $_GET['url'] = base64_encode($_GET[Tracking_Uri::DESTINED_URL]);
}

/* display position */
if (isset($_GET[Tracking_Uri::DISPLAY_POSITION])){
    $_GET['pos'] = $_GET[Tracking_Uri::DISPLAY_POSITION];
}

/* keyword */
if (isset($_GET[Tracking_Uri::KEYWORD])){
    $_GET['slkwd'] = $_GET[Tracking_Uri::KEYWORD];
}

/* keyword2 */
if (isset($_GET[Tracking_Uri::KEYWORD_SUPPLEMENTARY])){
    $_GET['kw2'] = $_GET[Tracking_Uri::KEYWORD_SUPPLEMENTARY];
}

if (isset($_GET[Tracking_Uri::SPONSOR_TYPE])){
    switch ($_GET[Tracking_Uri::SPONSOR_TYPE]) {
        case Tracking_Constant::SPONSOR_GOOGLE:
            $_GET['st'] = 2;
            break;

        case Tracking_Constant::SPONSOR_BAIDU:
            $_GET['st'] = 4;
            break;

        default:
            $_GET['st'] = 0;
            break;
    }
}

/* for coupon and banner */

/* category id */
if (isset($_GET[Tracking_Uri::CATEGORY_ID])){
    $_GET['c'] = $_GET[Tracking_Uri::CATEGORY_ID];
}

/* coupon id */
if (isset($_GET[Tracking_Uri::COUPON_ID])){
    $_GET['p'] = $_GET[Tracking_Uri::COUPON_ID];
}


/* router */
switch (isset($_GET[Tracking_Uri::BUILD_TYPE]) ? strtolower($_GET[Tracking_Uri::BUILD_TYPE]): '') {
    case 'sponsor':
        $_GET['pos'] = base64_encode($_GET['pos']);
        require_once dirname(__FILE__) . '/../../stats/sl_redir_core.php';
        break;

    case 'offer':
        $_GET['rdtp'] = __REDIRECT_SMARTER;
        require_once dirname(__FILE__).'/../../stats/redir_core.php';
        break;

    case 'coupon':
        $_GET['rdtp'] = __REDIRECT_CPA;
        require_once dirname(__FILE__).'/../../stats/redir_core.php';
        break;

    case 'banner':
        $_GET['rdtp'] = __REDIRECT_BANNER;
        require_once dirname(__FILE__).'/../../stats/redir_core.php';
        break;

    case 'special':
    case 'hotel':
    case 'ticket':
    case 'tour':
        $_GET['rdtp'] = __REDIRECT_SPECIAL;
        require_once dirname(__FILE__).'/../../stats/redir_core.php';
        break;

    default:
        require_once dirname(__FILE__).'/../../stats/redir_core.php';
        break;
}

$_GET  = $_GET_ORIG;
unset($_GET['chid'], $_GET['sltag'], $_GET['url'], $_GET['pos'], $_GET['slkwd'], $_GET['kw2'], $_GET['c'], $_GET['p'], $_GET['rdtp']);
/** end old db tracking **/