<?php
/*
* package_name : MerchantShipping.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: MerchantShipping.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Merchant;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;
use Custom\Util\PathManager;

class MerchantShipping extends TableGateway
{
    protected $table = "MerchantShipping";

    public function getMerchantShipping($merid) {
        $select = $this->getSql()->select();
        $where = array(
            'MerchantShipping.MerchantID' => $merid,
            'Shipping.IsStop' => 'NO',
        );
        $select->join('Shipping', 'MerchantShipping.ShippingID = Shipping.ShippingID', array('ShippingID', 'Name'), 'left');
        $select->where($where);
        $select->columns(array('MerchantID'));
        $resultSet = $this->selectWith($select);
        $ShippingList = $resultSet->toArray();
        if ($ShippingList) {
            foreach ($ShippingList as $Shipping) {
                $tmp[] = $Shipping['Name'];
            }
            $ShippingList = implode(',', $tmp);
        }
        return "";
    } 
}