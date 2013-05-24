<?php
/*
* package_name : MerchantCategory.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: MerchantCategory.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Merchant;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;
use Custom\Util\PathManager;

class MerchantCategory extends TableGateway
{
    protected $table = "MerchantCategory";
    /*
     * 推荐商家，绑定category
     */
    public function getRecommendMerchantList($siteid, $catid = null, $merid = null, $limit = 8) {
        $where = array(
            'SiteID' => $siteid,
            'CategoryID' => $catid
        );
        $select = $this->getSql()->select();
        $select->join('Merchant', 'Merchant.MerchantID = MerchantCategory.MerchantID', array('MerchantID', 'MerchantName', 'LogoFile'), 'inner');
        $select->where($where);
        $select->where("r_OnlineCouponCount > 0");
        if ($merid) {//排除当前merid
            $select->where("MerchantCategory.MerchantID != {$merid}");
        }
        $select->limit($limit);
        $select->order("Sequence DESC");
        //echo str_replace("\"", "", $select->getSqlString()); exit;
        $resultSet = $this->selectWith($select);
        $recommendMerchantList = $resultSet->toArray();
        foreach ($recommendMerchantList as $key => $recommendMerchant) {
            if ($siteid == '2') {
                $recommendMerchantList[$key]['MerchantDetailUrl'] = PathManager::getMerchantCateUrl($recommendMerchant['MerchantID'], $catid);
            }
        }
        return $recommendMerchantList;
    }
}