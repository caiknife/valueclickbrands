<?php
/*
* package_name : file_name
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: Google.php,v 1.2 2013/04/17 07:57:04 rizhang Exp $
*/
namespace CommModel\Ads;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;
use Custom\Sponsor\RequestAds;

class Google extends TableGateway
{
    /*
     * 获取3条广告
     */
    static public function getGoogleAds($keyword = '海外购物') {
        $AdsContent = RequestAds::getAdsScript( 
            array(
                'google' => array(
                    'splitCountArr' => array(3),
                    'keyword' => $keyword,
                    'IsHighlight' => true, 
                    'IsDisplayKeywordArr' => array(false),
                )
            )
        );
        return $AdsContent['google'];
    }
}