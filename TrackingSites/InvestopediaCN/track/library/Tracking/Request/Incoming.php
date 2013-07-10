<?php

/**
 * parse the incoming request and provide the parameters of url
 *
 * @category   Tracking
 * @package    Tracking_Request
 */
class Tracking_Request_Incoming extends Tracking_Request_Abstract
{
    /**
     * @param string $requestUri
     * @return void
     */
    public function __construct($requestUri = NULL)
    {
        parent::__construct($requestUri);

        $this->parse();
    }

    /**
     * parse url
     */
    public function parse()
    {
        //detect page type
        switch ($this->_params['PageType'] = $this->getPageType()) {
            case 'CHANNELPAGE':
                $this->setParam('ChannelId', $this->getChannelId());
                break;
            case 'CATEGORYPAGE':
                $this->setParam('CategoryId', $this->getCategoryId());
                break;
            case 'PRODUCTPAGE':
                $this->setParam('ProductId', $this->getProductId());
                break;
            case 'SEARCHPAGE':
                $this->setParam('Keyword', $this->getKeyword());
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * get page type of url
     * url relues list:
     *   home page     (/index.php|/|/?params=something)
     *   channel page  /cl--ci-{channel id}.html
     *   channel page  /category.php
     *   category page /pl--ch-{channel id}--ca-{category id}.html
     *   category page /prodlist.php
     *   product page  /pd--ch-{channel id}--pi-{product id}.html
     *   product page  /product.php
     *   search page   /se--qq-{keyword}.html
     *   search page   /search.php
     *
     * @return string
     */
    public function getPageType()
    {
        $url = $this->_requestUri;

        $patterns = array(
            '/^\/($|index\.php(.*)|([?&]{1})([^\/].*$))/i'  => 'HOMEPAGE',
            '/\/cl--ch-|---cl--ci-/i'                       => 'CHANNELPAGE',
            '/\/(pl|tl)--ch-|---pl--ch-|\/prodlist\.php/i'  => 'CATEGORYPAGE',
            '/\/pd--ch-|---pd--ch-|\/product\.php/i'        => 'PRODUCTPAGE',
            '/\/se--qq-|---se--qq-|\/search\.php/i'         => 'SEARCHPAGE',

            '/\/ps--ch-|---ps--ch-/i'                       => 'PRODUCTSPECPAGE',
            '/\/pr--ch-|---pr--ci-/i'                       => 'PRODUCTREVIEWPAGE',
            '/\/pw--ch-|---pw--ci-/i'                       => 'PRODUCTWRITEREVIEWPAGE',
            '/\/vd--ch-|---vd--ch-/i'                       => 'VIDEOLISTINGPAGE',
            '/\/mt--mi-|---mt--mi-/i'                       => 'MERCHANTINFOPAGE',
            '/\/mr--mi-|---mr--mi-/i'                       => 'MERCHANTREVIEWPAGE',
            '/\/rb--ch-|---rb--ci-/i'                       => 'MANUFACTUREREBATESPAGE',
        );

        foreach ($patterns as $pattern => $type) {
            if (preg_match($pattern, $url)) {
                return $type;
            }
        }
        //not match above
        return 'OTHER';
    }

    /**
     * get channel ID from url, only for channel page
     * url rules list:
     *   /cl--ci-{channel id}.html
     *
     * @return integer
     */
    public function getChannelId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/.*\/(cl|pr|pw|rb|pl|pd|tl|vd)--ch-(\d+).*/i',
            '/(ch=)(\d+)/i',
            '/---(cl|pr|pw|rb)--ci-(\d+).*/i',      //v3
            '/---(pl|pd)--ch-(\d+).*/i',            //v3
            '/(statChannelID=|chid=)(\d+)/i',       //v3
        );

        $matches = array();
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $result = $matches[2];
                break;
            }
        }
        return $result;
    }

    /**
     * get category ID, only for category page
     * url rules list:
     *   /pl--ch-{channel id}--ca-{category id}.html
     *   /pl--ch-{channel id}--ca-{category id}--{attribute variable}-{attribute valve}--bn-{page id}.html
     *   /pl--ch-{channel id}--ca-{category id}--vt-gridview.html
     *   /pl--ch-{channel id}--ca-{category id}--{attribute variable}-{attribute valve}--bn-{page id}--vt-gridview.html
     *   /pl--ch-{channel id}--ca-{category id}--{attribute variable}--1.html
     *   /prodlist.php?ca={category id}&time={time}......
     *
     * @return integer
     */
    public function getCategoryId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/\/pl--ch-(\d+)--ca-(\d+).*/i',
            '/\/tl--ch-(\d+)--ca-(\d+).*/i',
            '/\/vd--ch-(\d+)--ca-(\d+).*/i',
            '/\/prodlist\.php[?&].*(ca=)(\d+)/i',
            '/---cl--ci-(\d+)--cd-(\d+).*/i',           //v3
            '/---pl--ch-(\d+)--ca-(\d+).*/i',           //v3
            '/\/prodlist\.php[?&].*(cate=)(\d+)/i',     //v3
        );

        $matches = array();
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $result = $matches[2];
                break;
            }
        }
        return $result;

    }

    /**
     * get product ID from url, only for product page
     * url relues list:
     *   /pd--ch-{channel id}--pi-{product id}.html
     *   /product.php?pi=6&.......
     *
     * @return int
     */
    public function getProductId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/\/pd--ch-(\d+)--pi-(\d+).*/i',
            '/\/product.php[?&].*(pi=)(\d+)/i',
            '/---pd--ch-(\d+)--pi-(\d+).*/i',          //v3
            '/\/product.php[?&].*(prodid=)(\d+)/i',    //v3
        );

        $matches = array();
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $result = $matches[2];
                break;
            }
        }
        return $result;

    }

    /**
     * get source tag from url
     * @return string  source tag from url
     */
    public function getSource()
    {
        $source = '';
        $match = array();
        $pattern = '/(=|&|\?|\/)source=([^&]*)/i';
        if (preg_match($pattern, $this->_requestUri, $match)) {
            $source = empty($match[2]) ? 'none' : $match[2];
        }
        return $source;
    }

    /**
     * get Source Group from source tag
     * @return string  source group form source tag
     */
    public function getSourceGroup()
    {
        $source = $this->getSource();

        $sourceGroup = '';
        if (!empty($source)) {
            $tmpArr = explode("_", $source);
            $sourceGroup = $tmpArr[0];
        }
        return $sourceGroup;
    }

    /**
     * get keyword from url, for search page
     * @return string
     */
    public function getKeyword()
    {
        if ((bool)$keyword = $this->getParam('qq')) {
            return $keyword;
        } else {
            return $this->getParam('q');
        }
    }

    /**
     * if source
     *
     * @return boolean
     */
    public function hasSource()
    {
        return (boolean)preg_match('/(\?|&)source=/i', $this->_requestUri);
    }
    
    /**
     * has google analytics parameter
     * @return boolean
     */
    public function hasGAParam()
    {
        return (boolean)preg_match('/(\?|&)(utm_.*?|gclid)=/i', $this->_requestUri);
    }

    /**
     * remove source tag from url
     * ?source=    NULL
     * ?source=&   &
     * &source=    NULL
     * &source=&   &
     * =source=    NULL
     * =source=&   &
     * @return string
     */
    public function normalize()
    {
        $url = $this->_requestUri;

       //$url = preg_replace('/source=[^&]*/i', '', $url);
        $url = preg_replace('/(\?|&)(source|gclid|utm_.*?)=[^&]*/i', '$1', $url);
        $url = preg_replace('/[&]+/', '&', $url);
        $url = preg_replace('/\?[&]+/', '?', $url);
        $url = preg_replace('/[?&]+$/', '', $url);

        return $url;
    }

    /**
     * is asynchronous page?
     *
     * @return boolean
     */
    public function isAsynchronousPage()
    {
        return strcasecmp($this->getParam(Tracking_Uri::BUILD_TYPE, ''), 'async')===0;
    }

    /**
     * not include some page
     * @return boolean
     */
    public function isSpecialPage()
    {
        $pattern = '/\/(css|jscript|images)\/|(favicon\.ico)|async[^\.]*\.php/i';
        return (boolean)preg_match($pattern, $this->_requestUri) || $this->isAsynchronousPage();
    }

    /**
     * not include some page
     * @return boolean
     */
    public function isRedirPage()
    {
        $pattern = '/redir\.php/i';
        return (boolean)preg_match($pattern, $this->_requestUri);
    }
}
