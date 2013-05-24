<?php
/*
* package_name : MerchantPayment.php
* ------------------
* typecomment
*
* PHP versions 5
* 
* @Author   : Richie Zhang(rizhang@mezimedia.com)
* @Copyright: Copyright (c) 2004-2011 Mezimedia Com. (http://www.mezimedia.com)
* @license  : http://www.mezimedia.com/license/
* @Version  : CVS: $Id: MerchantPayment.php,v 1.1 2013/04/15 10:56:26 rock Exp $
*/
namespace CommModel\Merchant;

use Zend\Db\Adapter\Adapter;
use Custom\Db\TableGateway\TableGateway;

class MerchantPayment extends TableGateway
{
    protected $table = "MerchantPayment";

    public function getMerchantPayment($merid, $showName = true) {
        $select = $this->getSql()->select();
        $where = array(
            'MerchantPayment.MerchantID' => $merid,
            'Payment.IsStop' => 'NO',
        );
        $select->join('Payment', 'MerchantPayment.PaymentID = Payment.PaymentID', array('PaymentID', 'Name'), 'left');
        $select->where($where);
        $select->columns(array());
        $resultSet = $this->selectWith($select);
        $resultArray = $resultSet->toArray();
        $tmp = array();
        if ($resultArray) {
            foreach ($resultArray as $result) {
                if ($showName) {
                    $tmp[] = $result['Name'];
                } else {
                    $tmp[] = $result['PaymentID'];
                }
            }
        }
        return $tmp;
    } 
}