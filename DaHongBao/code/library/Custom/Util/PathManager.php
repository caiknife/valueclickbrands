<?php
/*
 * package_name : PathManager.php
 * ------------------
 * 共同函数
 *
 * PHP versions 5
 * 
 * @Author   : thomas(thomas_fu@mezimedia.com)
 * @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
 * @license  : http://www.mezimedia.com/license/
 * @Version  : CVS: $Id: PathManager.php,v 1.1 2013/04/15 10:56:31 rock Exp $
 */
namespace Custom\Util;

use Custom\Util\Utilities;

class PathManager 
{
    /**
     * 
     * Merchant List
     */
    public static function getAllMerchantUrl(){
        return "/all-merchant/";
    }

    /**
     * 
     * Mrchant Type [A-Z]{1}
     */
    public static function getMerchantKeyUrl($key){
        if (empty($key)) {
            return self::getAllMerchantUrl();
        }

        return "/all-merchant-{$key}/";
    }

    /**
     * 
     * Merhant Detail [0-9]+
     */
    public static function getMerchantDetailUrl($merid, $page = null){
        $merid = (int)$merid;
        if (empty($page)) {
            return "/merchant-{$merid}/";
        }
        return "/merchant-{$merid}/page-{$page}/";
    }

    /**
     * 
     * Merchant Category
     */
    public static function getMerchantCateUrl($merid, $catid) {
        $merid = (int)$merid;
        $catid = (int)$catid;
        return "/category-{$catid}-merchant-{$merid}/";
        
    }

    /**
     * 
     * All Category List
     */
    public static function getAllCateUrl() {
        return "/category/";
    }

    /**
     * 
     * Category List
     */
    public static function getCateListUrl($catid, $page = null) {
        $catid = (int)$catid;
        if ($page) {
            return "/category-{$catid}/page-{$page}/"; 
        }
        return "/category-{$catid}/";
    }

    /**
     * 
     * Deal List
     */
    public static function getDealsListUrl() {
        return "/deals/";
    }

    /**
     * 
     * Search Coupon
     */
    public static function getSearchCouponUrl($keyword) {
        return "/s-coupon-{$keyword}/";
    }

    /**
     * 
     * Search Deals
     */
    public static function getSearchDealsUrl($keyword) {
        return "/s-deals-{$keyword}/";
    }

    /**
     * 
     * Article Category
     */
    public static function getArticleCateUrl($catid = null) {
        if (empty($catid)) {
            return "/help/";
        }
        return "/help-{$catid}/";
    }

    /**
     * 
     * Article Detail
     */
    public static function getArticleDetailUrl($aid) {
        return "/article-{$aid}/";
    }

    //------------------ 大红包  ------------------//
    /*
     * Coupon Overall List Page
     */
    public function getDhbCouponUrl($page = null, $sortby = null) {
        $str = "/quan-all/";
        if ($page) {
            $str .= 'page-'.$page.'/';
        }
        if ($sortby) {
            $str .= 'sortby-'.$sortby.'/';
        }
        return $str;
    }

    /*
     * Coupon Category List Page
     */
    public static function getDhbCouponCateUrl($cid, $page = null, $sortby = null) {
        $str = "/category-{$cid}/";
        if ($page) {
            $str .= 'page-'.$page.'/';
        }
        if ($sortby) {
            $str .= 'sortby-'.$sortby.'/';
        }
        return $str;
    }

    /*
     * Coupon Detail Page
     */
    public static function getDhbCouponDetailUrl($id) {
        return "/quan-{$id}/";
    }

    /*
     * Merchant Detail Page
     */
    public static function getDhbMerchantDetailUrl($merid) {
        return "/merchant-{$merid}/";
    }

    /*
     * Deals List Page
     */
    public static function getDhbDealsUrl($page = null, $sortby = null) {
        $str = "/cuxiao/";
        if ($page) {
            $str .= 'page-'.$page.'/';
        }
        if ($sortby) {
            $str .= 'sortby-'.$sortby.'/';
        }
        return $str;
    }

    /*
     * Deals Detail Page
     */
    public static function getDhbDealsDetailurl($id) {
        return "/cuxiao-{$id}/";
    }

    /*
     * Search coupon page
     */
    public static function getDhbSearchCouponUrl($keyword) {
        return "/s-quan-{$keyword}/";
    }

    /*
     * Search Deals page
     */
    public static function getDhbSearchDealsUrl($keyword, $page = null, $sortby = null) {
        $str = "/s-cuxiao-{$keyword}/";
        if ($page) {
            $str .= 'page-'.$page.'/';
        }
        if ($sortby) {
            $str .= 'sortby-'.$sortby.'/';
        }
        return $str;
    }

    /**
     * 
     * Article Category
     */
    public static function getDhbArticleCateUrl($catid = null) {
        if (empty($catid)) {
            return "/help/";
        }
        return "/help-{$catid}/";
    }

    /*
     * Article detail page
     */
    public static function getDhbArticleDetailUrl($aid) {
        return "/article-{$aid}/";
    }
}
 ?>