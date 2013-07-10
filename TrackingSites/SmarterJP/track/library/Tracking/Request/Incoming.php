<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Request
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2013/06/27 07:54:18 jjiang Exp $
 */

/**
 * parse the incoming request and provide the parameters of url
 *
 * @category   Tracking
 * @package    Tracking_Request
 */
class Tracking_Request_Incoming extends Tracking_Request_Abstract
{
    /**
     * convert encoding
     *
     * @param string $str
     * @return string
     */
    private function _convertEncoding($str)
    {
        $str = urldecode($str);
        if(mb_detect_encoding($str, "UTF-8,SJIS") == "SJIS") {
            $str = mb_convert_encoding($str, "UTF-8", "UTF-8,SJIS");
        }
        return urlencode($str);
    }

    private function _parseUri($matches)
    {
        return $matches[1] . $this->_convertEncoding($matches[2]);
    }

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
		//move the url parse to offline.
		return '';
		/*
        $url = $this->_requestUri;

        $patterns = array(
            '/^\/$/i'                                                                => 'homepage',
            '/^\/se--qq-[^\/]+--pt-[0-9]+.*\.html$/i'                                => 'search_cat',
            '/^\/se--qq-[^\/]+.*\.html|^\/se--kw-[^\/]+.*\.html$/i'                  => 'search',
            '/^\/[^\/]+-[0-9]+\/[^\/]+-[0-9]+-[0-9]+\/se--qq-[^\/]+--.*$/i'          => 'search_prod_list',
            '/^\/[^\/]+-[0-9]+\/$/i'                                                 => 'channel',
            '/^\/[^\/]+-[0-9]+\/[^\/]+-[0-9]+\/$/i'                                  => 'prod_list',
            '/^\/[^\/]+-[0-9]+\/[^\/]+-[0-9]+-[0-9]+\/$/i'                           => 'prod_list_brand',
            '/^\/[^\/]+-[0-9]+\/[^\/]+-[0-9]+-[0-9]+\/[^\/]+-[0-9]+$/i'              => 'prod_list_filters',
            '/^\/[^\/]+-[0-9]+\/[^\/]+-[0-9]+\/allattr-[0-9]+\.html$/i'              => 'prod_list_all',
            '/^\/[^\/]+-[0-9]+\/prod-[0-9]+\/$/i'                                    => 'prod_detail',
            '/^\/[^\/]+-[0-9]+\/pspec-[0-9]+\/$/i'                                   => 'prod_spec',
            '/^\/[^\/]+-[0-9]+\/preview-[0-9]+\/$/i'                                 => 'prod_review',
            '/^\/[^\/]+-[0-9]+\/pwrite-[0-9]+\/$/i'                                  => 'prod_write_review',
            '/^\/[^\/]+-[0-9]+\/mreview-[0-9]+\/$/i'                                 => 'mer_review',
            '/^\/[^\/]+-[0-9]+\/minfo-[0-9]+\/$/i'                                   => 'mer_info',
            '/^\/[^\/]+-[0-9]+\/mwrite-[0-9]+\/$/i'                                  => 'mer_write_review',
            '/^\/[^\/]+-[0-9]+\/sotre-[0-9]+-[0-9]+\/$/i'                            => 'mer_shop',
            '/^\/[^\/]+-[0-9]+\/mcate-[0-9]+-[0-9]+\/$/i'                            => 'mer_cate',
            '/^\/pcat--[0-9]+----[^\/]+\.html$/i'                                    => 'brand',
            '/^\/specialbanner\/[^\/]+\.htm.*$/i'                                    => 'Special Banner Page',     
            '/^\/cl-[^\/]+\/[^\/]+-[0-9]+\/allattr-1\.htm$/i'                        => 'Merchent Attribute Page',
            '/^\/cl-[^\/]+\/[^\/]+-[0-9]+\/allattr-2\.htm$/i'                        => 'Brand Attribute Page',
            '/^\/cl-[^\/]+\/[^\/]+-[0-9]+\/allattr-3\.htm$/i'                        => 'Category Attribute Page',
            '/^\/[^\/]+\/mfid-[0-9]+\.htm$/i'                                        => 'Shop by Brand Page',
        );

        foreach ($patterns as $pattern => $type) {
            if (preg_match($pattern, $url)) {
                return $type;
            }
        }
        //not match above
        return 'OTHER';
		*/
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
            '/(ch|chid|statChannelID)=(\d+)/i',
            '/^\/(pcat)--(\d+)-(\d+)/i',
            '/\/\w+-\d+\/(mcate|minfo|mreview|mwrite|store)-(\d+)-(\d+)/i',     // smjp
            '/^\/(\w+)-(\d+)\//i',                                              // smjp
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
        '/^\/pcat--(\d+)-(\d+)/i',
        '/^\/\w+-(\d+)\/pcat-(\d+)/i',                                      // smjp
        '/\/\w+-\d+\/(mcate|minfo|mreviewe|store)-\d+-(\d+)/i',             // smjp
        '/\/prodlist\.php[?&].*(ca|cate)=(\d+)/i',
        '/^\/\w+-(\d+)\/(?!prod).*-([0-9]+)\/allattr-/i',                   // smjp
        '/^\/se-\w+-(\d+)-\d+-(\d+).htm/',                                  // smjp
        '/^\/\w+-(\d+)\/(?!pwrite|preview|pspec|prod|pperror|mwrite).*-(\d+)-\d+\//i',    // smjp Sub Category & Leaf Category Page
        '/^\/\w+-(\d+)\/(?!pwrite|preview|pspec|prod|pperror|mwrite).*-(\d+)\//i',    // smjp Sub Category & Leaf Category Page
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
            '/\/(pwrite|preview|pspec|prod|pperror)-(\d+)\//i',
            '/\/(mwrite)-\d+-(\d+)/i',            // smjp
            '/\/(?:product|prodwritereview).php[?&].*(prodid|pi)=(\d+)/i',     // v3
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
        $pattern = '/(=|&|\?|#|\/)source=([^&]*)/i';
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
        if ((bool)$innerParas = $this->getParam('InnerPara')) {
            $innerParas = explode('-', $innerParas);
            return $innerParas[0];
        } elseif ((bool)$keyword = $this->getParam('qq')) {
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
        //return (boolean)preg_match("/source=/i", $this->_requestUri);
        return (boolean)preg_match('/(\?|&)source=/i', $this->_requestUri);
    }

    
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
        $pattern = '/\/(css|jscript|js|images|img|ajax)\/|(favicon\.ico)|(mezi_ads|async[^\.]*)\.php/i';
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