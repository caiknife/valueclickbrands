<?php
return array(
    'factories' => array(
        'Merchant' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Merchant\Merchant($dbAdapter, $svAdapter);
            return $table;
        },
        'Subscription' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Subscription\Subscription($dbAdapter, $svAdapter);
            return $table;
        },
        'Category' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Category\Category($dbAdapter, $svAdapter);
            return $table;
        },
        'MerchantCategory' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Merchant\MerchantCategory($dbAdapter, $svAdapter);
            return $table;
        },
        'Article' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Article\ArticleTable($dbAdapter, $svAdapter);
            return $table;
        },
        'Coupon' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\Coupon($dbAdapter, $svAdapter);
            return $table;
        },
        'CouponCategory' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\CouponCategory($dbAdapter, $svAdapter);
            return $table;
        },
        'CouponSearch' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\CouponSearch($dbAdapter, $svAdapter);
            return $table;
        },
        'CouponExtra' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\CouponExtra($dbAdapter, $svAdapter);
            return $table;
        },
        'CouponCode' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\CouponCode($dbAdapter, $svAdapter);
            return $table;
        },
        'CouponCodeUser' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Coupon\CouponCodeUser($dbAdapter, $svAdapter);
            return $table;
        },
        'ArticleCategory' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\DefaultAdapter');
            $svAdapter = $sm->get('Custom\Db\Adapter\DefaultSlaveAdapter');
            $table = new CommModel\Article\ArticleCategoryTable($dbAdapter, $svAdapter);
            return $table;
        },
        'Favorite' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\UserAdapter');
            $table = new CommModel\Coupon\Favorite($dbAdapter);
            return $table;
        },
        'UserCouponCode' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\UserAdapter');
            $table = new CommModel\Coupon\UserCouponCode($dbAdapter);
            return $table;
        },
        'User' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\UserAdapter');
            $table = new CommModel\Coupon\User($dbAdapter);
            return $table;
        },
        'UserPoint' => function($sm) {
            $dbAdapter = $sm->get('Custom\Db\Adapter\UserAdapter');
            $table = new CommModel\Coupon\UserPoint($dbAdapter);
            return $table;
        },
        'UserOnline' => function($sm) {
        $dbAdapter = $sm->get('Custom\Db\Adapter\UserAdapter');
        $table = new CommModel\Coupon\UserOnline($dbAdapter);
        return $table;
        }
    ),
);