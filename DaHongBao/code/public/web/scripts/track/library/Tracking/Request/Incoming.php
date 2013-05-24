<?php
/**
 * Mezimedia Tracking
 *
 * @category   Tracking
 * @package    Tracking_Request
 * @author     Ben <ben_yan@mezimedia.com>
 * @copyright  Copyright (c) 2004-2008 Mezimedia Com. (http://www.mezimedia.com)
 * @license    http://www.mezimedia.com/license/
 * @version    CVS: $Id: Incoming.php,v 1.1 2013/04/15 10:58:19 rock Exp $
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
            '/\/cl--ch-|---cl--ci-|\/category\.php/i'       => 'CHANNELPAGE',
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

        /* not match above */
        return 'OTHER';
    }

    /**
     * get channel ID from url
     *
     * @return integer
     */
    public function getChannelId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/(ch=|statChannelID=|chid=)(\d+)/i',
            '/^\/(?:[^-]*)-(\d+)\/(?:mcate|mstore|minfo|mwrite|mreview|mship)-(\d+)-(\d+)/i',     // smcn
            '/^\/(\w+)\/list-(\d+)-(\d+)/i',
            '/^\/merchant\/(\w+)-(\d+)/i',
            '/^\/(?:web\/)?([^-\/]*)-(\d+)\//',             // smcn
            '/^\/([^-]*)-se-ch-(\d+)-c-(\d+)/i',            // smcn
            '/^\/sitemap\/(\w+)-(\d+)\/\w+-(\d+)-search/i', // smcn
        );

        $matches = array();
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $result = (integer)$matches[2];
                break;
            }
        }
        return $result;
    }

    /**
     * get category ID
     *
     * @return integer
     */
    public function getCategoryId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/^\/(?:[^-]*)-\d+\/(?:mcate|mstore|minfo|mwrite|mreview|mship)-(\d+)-(\d+)/i',     // smcn
            '/^\/[^-]*-se-ch-(\d+)-c-(\d+)/i',            // smcn
            '/^\/sitemap\/\w+-(\d+)\/\w+-(\d+)-search/i', // smcn
            '/^\/\w+\/list-(\d+)-(\d+)/i',
            '/\/category\/(.*)-(\d+)\/(.*)/i',
            '/\/category\/([^-]+)-(\d+)-(.*)/i',        // smcn
            '/\/prodlist\.php[?&].*(ca|cate)=(\d+)/i',
            '/\/category\.php[?&].*(catid=)(\d+)/i',     // smcn
        );

        $matches = array();
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $result = (integer)$matches[2];
                break;
            }
        }

		if($result == 0){
			$pattern = "/\/(computers|electronics|communication|OfficeDevice)-(\d+)\/([^\/]*)-([0-9]+)\//i";
			if(preg_match($pattern, $url, $matches)){
				if(strpos($matches[3], 'prod') === false && strpos($matches[3], 'preview') === false && strpos($matches[3], 'pwrite') === false && strpos($matches[3], 'pperror') === false && strpos($matches[3], 'pspec') === false ){
					$result = (integer)$matches1[4];
				}else{
					$result = 0;
				}
			}
		}

        return $result;
    }

    /**
     * get product ID from url, only for product page
     *
     * @return int
     */
    public function getProductId()
    {
        $result = 0;
        $url = $this->_requestUri;

        $patterns = array(
            '/\/(pwrite|preview|pspec|prod)-(\d+)\//i',
            '/\/product.php[?&].*(prodid|pi)=(\d+)/i',     // v3
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
     *
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
        return (boolean)preg_match("/source=/i", $this->_requestUri);
    }

    /**
     * remove source tag from url
     * ?source=    NULL
     * ?source=&   &
     * &source=    NULL
     * &source=&   &
     * =source=    NULL
     * =source=&   &
     *
     * @return string
     */
    public function normalize()
    {
        return preg_replace(
            array('/source=[^&]*/i', '/[&]+/', '/\?[&]+/', '/[?&]+$/'),
            array('',                '&',      '?',        ''),
            $this->_requestUri
        );
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
        $pattern = '/\/(css|jscript|js|images|ats)\/|(favicon\.ico)|async[^\.]*\.php|stp\.php/i';
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