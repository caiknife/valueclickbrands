<?php
class UserScoreDao {

    const REFRESH_LIMIT_TIME = 600;

    public $POSTBBSSCORE = 100;
    public $POSTCOUPONSCORE = 500;
    public $REFRESHSCORE = 1;
    public $COMMITSCORE = 10;
    public $SUPPORTSCORE = 20;
    public $OPPOSESCORE = -100;
    //private static $COMMITSCORE = 10;

    private $userid;
    private $ip;

    //
    function __construct($userid) {
        $this->userid = $userid;
        if(isset($_SERVER["HTTP_RLNCLIENTIPADDR"]) && $_SERVER["HTTP_RLNCLIENTIPADDR"] !="") {
                $this->ip = $_SERVER["HTTP_RLNCLIENTIPADDR"];
        } else {
                $this->ip = $_SERVER['REMOTE_ADDR'];
        }
    }

    //�������
    private function checkInput() {
        if (empty ($this->userid)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function getUserScoreState(){
        return 0;
//        $userid = $this->userid;
//        $sql = "SELECT * FROM UserScoreStatus WHERE UserID=$userid";
//        $getrow = DBQuery :: instance()->getRow($sql);
//        return $getrow;
    }

    public function getUserCouponCount(){
        return 0;
//        $userid = $this->userid;
//        $sql = "SELECT count(*) FROM Coupon INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) ";
//        $sql .= "WHERE pw_threads.authorid=$userid AND Coupon.isActive=1 AND (Coupon.ExpireDate >= CURDATE() || Coupon.ExpireDate='0000-00-00') AND Coupon.CouponType!=9";
//        $getrow = DBQuery :: instance()->getOne($sql);
//        return $getrow;    
    }
    
    public function getUserCouponExpireCount(){
        return 0;
//        $userid = $this->userid;
//        $sql = "SELECT count(*) FROM Coupon INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) ";
//        $sql .= "WHERE pw_threads.authorid=$userid AND Coupon.isActive=1 AND (Coupon.ExpireDate < CURDATE() && Coupon.ExpireDate!='0000-00-00') AND Coupon.CouponType!=9";
//        $getrow = DBQuery :: instance()->getOne($sql);
//        return $getrow;    
    }
    
    public function getUserDiscountCount(){
        return 0;
//        $userid = $this->userid;
//        $sql = "SELECT count(*) FROM Coupon INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) ";
//        $sql .= "WHERE pw_threads.authorid=$userid AND Coupon.isActive=1 AND (Coupon.ExpireDate >= CURDATE() || Coupon.ExpireDate='0000-00-00') AND Coupon.CouponType=9";
//        $getrow = DBQuery :: instance()->getOne($sql);
//        return $getrow;    
    }
    
    public function getUserDiscountExpireCount(){
        return 0;
//        $userid = $this->userid;
//        $sql = "SELECT count(*) FROM Coupon INNER JOIN pw_threads ON (pw_threads.dhbid = Coupon.Coupon_) ";
//        $sql .= "WHERE pw_threads.authorid=$userid AND Coupon.isActive=1 AND (Coupon.ExpireDate < CURDATE() && Coupon.ExpireDate!='0000-00-00') AND Coupon.CouponType=9";
//        $getrow = DBQuery :: instance()->getOne($sql);
//        return $getrow;    
    }
    
    public function getOtherScoreCount(){
        return 0;
//        $userid = $this->userid;
//        $limittime = getDateTime("Y-m-d H:i:s",time()-24*60*60*30);
//        $sql = "SELECT count(*) FROM UserScore WHERE UserID=$userid AND UpdateTime>'$limittime' AND Operate=10";
//        $getrow = DBQuery :: instance()->getOne($sql);
//        return $getrow;    
    }

    //input:$keytype:1--bbs;2--discount;3--coupon
    //      $key:couponid
    //        $userid:author user id
    //        $operate
    //        $checktype:1--ˢ��;2--����;3--֧��;4--Ͷ��;5--����;6--ɾ������;7--ɾ��֧��;8--ɾ��Ͷ��;9--ɾ������;10--����
    public function addScore($operatetype, $keyid, $keytype, $vuserid, $operatemore, $score, $db = "") {
            //�������
        return;
        if (!self :: checkInput())
            return;
        //����Ȩ�޼��
        $check = self :: checkStatus($operatetype, $keyid, $keytype, $db);
        if ($check) {
            switch ($operatetype) {
                case "REFRESH" :
                    $score = $this->REFRESHSCORE;
                    $checktype = 1;
                    break;
                case "COMMIT" :
                    $score = $this->COMMITSCORE;
                    $checktype = 2;
                    break;
                case "SUPPORT" :
                    $score = $this->SUPPORTSCORE;
                    $checktype = 3;
                    break;
                case "OPPOSE" :
                    $score = $this->OPPOSESCORE;
                    $checktype = 4;
                    break;
                case "POST" :
                    if ($keytype == "bbs") {
                        $score = $this->POSTBBSSCORE;
                    } else {
                        $score = $this->POSTCOUPONSCORE;
                    }
                    $checktype = 5;
                    break;
                case "DELETECOMMIT" :
                    $score = -$this->COMMITSCORE;
                    $checktype = 6;
                    break;
                case "DELETESUPPORT" :
                    $score = -$this->SUPPORTSCORE;
                    $checktype = 7;
                    break;
                case "DELETEOPPOSE" :
                    $score = -$this->OPPOSESCORE;
                    $checktype = 8;
                    break;
                case "DELETEPOST" :
                    if ($keytype == "bbs") {
                        $score = -$this->POSTBBSSCORE;
                    } else {
                        $score = -$this->POSTCOUPONSCORE;
                    }
                    $checktype = 9;
                    break;
                case "OTHER" :
                    $score = $score;

                    $sql = "INSERT INTO UserScoreOperateMore (`ID`,`OperateDetail`) VALUES ('','$operatemore')";
                    if (is_object($db)) {
                        $rs = $db->query($sql);
                        $operatemore = $db->mysql_insert_id();
                    } else {
                        $rs = DBQuery :: instance()->executeQuery($sql);
                        $operatemore = DBQuery :: instance()->getInsertID();
                    }
                    $checktype = 10;
                    break;
                default :
                    return;
            }


            //���뵥����¼
            $sql = "INSERT INTO UserScore ";
            $sql .= "(`ID`,`Key`,`KeyType`,`UserID`,`IP`,`UpdateTime`,`Operate`,`OperateMore`,`Score`,`VisitUserID`) VALUES ";
            $sql .= "('','$keyid','$keytype','".$this->userid."','".$this->ip."',NOW(),'$checktype','$operatemore','$score','$vuserid')";

            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
            if($rs){
                $this->addScoreStatus($operatetype, $keyid, $keytype , $vuserid, $db, $score);
            }

        } else {
            return; //�޲���Ȩ��ֱ�ӷ���
        }
    }

    //input:$checktype:1--ˢ��;2--����;3--֧��;4--Ͷ��;5--����;6--ɾ������;7--ɾ��֧��;8--ɾ��Ͷ��;9--ɾ������;10--����
    //      $key:couponid
    private function checkStatus($operatetype, $keyid, $keytype, $db = "") {

        switch ($operatetype) { //��������Ƿ���Ȩ��addscore��sql
            case "REFRESH" :
                //���ˢ��Ȩ��ʱ,��Ȼ��Ҫkeyid���û�ip
                if (empty ($keyid) || empty ($this->ip))
                    return false;
                $limittime = getDateTime("Y-m-d H:i:s", time() - self :: REFRESH_LIMIT_TIME);
                $sql = "SELECT ID FROM UserScore WHERE `Key` = $keyid AND IP = '".$this->ip."' AND Operate=1 AND UpdateTime > '$limittime'";
                if (is_object($db)) {
                    $rs = $db->get_one($sql);
                } else {
                    $rs = DBQuery :: instance()->getOne($sql);
                }
                if ($rs) {
                    return false;
                } else {
                    return true;
                }
            default :
                return true;
        }

    }
    
    private function addHongBao($hongbao, $db = "") {
        $userid = $this->userid;
        $sql = "UPDATE pw_memberdata SET money=money+$hongbao WHERE uid=$userid";
        if (is_object($db)) {
            $rs = $db->query($sql);
        } else {
            $rs = DBQuery :: instance()->executeQuery($sql);
        }
    }

    private function addScoreStatus($operatetype, $key, $keytype, $vuserid, $db = "", $score = "") {
        $userid = $this->userid;
        $sql = "SELECT * FROM UserScoreStatus WHERE UserID = '$userid'";
        if (is_object($db)) {
            $lastrow = $db->get_one($sql);
        } else {
            $lastrow = DBQuery :: instance()->getRow($sql);
        }
        if (empty ($lastrow)) {
            $sql = "INSERT INTO UserScoreStatus ";
            $sql .= "(`ID`,`UserID`,`UpdateTime`,`ScoreLatest`,`CouponVisit`,`CouponReview`,`CouponSupport`,`CouponOppose`,`CouponPost`,`DiscountVisit`,`DiscountReview`,`DiscountSupport`,`DiscountOppose`,`DiscountPost`,`BBSPost`,`BBSReply`,`CouponReviewDelete`,`CouponSupportDelete`,`CouponOpposeDelete`,`CouponPostDelete`,`DiscountReviewDelete`,`DiscountSupportDelete`,`DiscountOpposeDelete`,`DiscountPostDelete`,`BBSPostDelete`,`BBSReplyDelete`) VALUES ";
            $sql .= "('','$userid',NOW(),'0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0')";
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "REFRESH") { //ˢ��
            if ($keytype == "coupon") {
                $visitnum = $lastrow['CouponVisit'] + $this->REFRESHSCORE;
                if ($visitnum % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponVisit`='$visitnum' WHERE UserID='$userid'";
            } else {
                $visitnum = $lastrow['DiscountVisit'] + $this->REFRESHSCORE;
                if ($visitnum % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountVisit`='$visitnum' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "COMMIT") { //����
            if ($keytype == "bbs") { //bbs����
                $TotalReview = $lastrow['BBSReply'] + $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`BBSReply`='$TotalReview' WHERE UserID='$userid'";
            }
            elseif ($keytype == "discount") { //discount
                $TotalReview = $lastrow['DiscountReview'] + $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountReview`='$TotalReview' WHERE UserID='$userid'";
            } else {
                $TotalReview = $lastrow['CouponReview'] + $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponReview`='$TotalReview' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "DELETECOMMIT") { //����ɾ��
            if ($keytype == "bbs") { //bbs����
                $TotalReview = $lastrow['BBSReplyDelete'] - $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(-1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`BBSReplyDelete`='$TotalReview' WHERE UserID='$userid'";
            }
            elseif ($keytype == "discount") { //discount
                $TotalReview = $lastrow['DiscountReviewDelete'] - $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(-1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountReviewDelete`='$TotalReview' WHERE UserID='$userid'";
            } else {
                $TotalReview = $lastrow['CouponReviewDelete'] - $this->COMMITSCORE;
                if ($TotalReview % 100 == 0) {
                    self :: addHongBao(-1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponReviewDelete`='$TotalReview' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }

        }

        if ($operatetype == "SUPPORT") { //֧��
            if ($keytype == "coupon") {
                $TotalSupport = $lastrow['CouponSupport'] + $this->SUPPORTSCORE;
                if ($TotalSupport % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponSupport`='$TotalSupport' WHERE UserID='$userid'";
            } else {
                $TotalSupport = $lastrow['DiscountSupport'] + $this->SUPPORTSCORE;
                if ($TotalSupport % 100 == 0) {
                    self :: addHongBao(1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountSupport`='$TotalSupport' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "DELETESUPPORT") { //ɾ��֧��
            if ($keytype == "coupon") {
                $TotalSupport = $lastrow['CouponSupport'] - $this->SUPPORTSCORE;
                if ($TotalSupport % 100 == 0) {
                    self :: addHongBao(-1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponSupport`='$TotalSupport' WHERE UserID='$userid'";
            } else {
                $TotalSupport = $lastrow['DiscountSupport'] - $this->SUPPORTSCORE;
                if ($TotalSupport % 100 == 0) {
                    self :: addHongBao(-1, $db);
                }
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountSupport`='$TotalSupport' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "OPPOSE") { //Ͷ��
            if ($keytype == "coupon") {
                $TotalOppose = $lastrow['CouponOppose'] + $this->OPPOSESCORE;
                self :: addHongBao(-1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponOppose`='$TotalOppose' WHERE UserID='$userid'";
            } else {
                $TotalOppose = $lastrow['DiscountOppose'] + $this->OPPOSESCORE;
                self :: addHongBao(-1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountOppose`='$TotalOppose' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "DELETEOPPOSE") { //ɾ��Ͷ��
            if ($keytype == "coupon") {
                $TotalOppose = $lastrow['CouponOppose'] - $this->OPPOSESCORE;
                self :: addHongBao(1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponOppose`='$TotalOppose' WHERE UserID='$userid'";
            } else {
                $TotalOppose = $lastrow['DiscountOppose'] - $this->OPPOSESCORE;
                self :: addHongBao(1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountOppose`='$TotalOppose' WHERE UserID='$userid'";
            }
            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "POST") { //����        
            if ($keytype == "bbs") { //bbs����
                $BBSPost = $lastrow['BBSPost'] + $this->POSTBBSSCORE;
                self :: addHongBao(1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`BBSPost`='$BBSPost' WHERE UserID='$userid'";
            }
            elseif ($keytype == "discount") { //discount����
                $DiscountPost = $lastrow['DiscountPost'] + $this->POSTCOUPONSCORE;
                self :: addHongBao(5, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountPost`='$DiscountPost' WHERE UserID='$userid'";
            }
            elseif ($keytype == "coupon") {
                $CouponPost = $lastrow['CouponPost'] + $this->POSTCOUPONSCORE;
                self :: addHongBao(5, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponPost`='$CouponPost' WHERE UserID='$userid'";
            }

            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "DELETEPOST") { //ɾ������
            if ($keytype == "bbs") { //bbs����
                $BBSPost = $lastrow['BBSPostDelete'] - $this->POSTBBSSCORE;
                self :: addHongBao(-1, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`BBSPostDelete`='$BBSPost' WHERE UserID='$userid'";
            }
            elseif ($keytype == "discount") { //discount����
                $DiscountPost = $lastrow['DiscountPostDelete'] - $this->POSTCOUPONSCORE;
                self :: addHongBao(-5, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`DiscountPostDelete`='$DiscountPost' WHERE UserID='$userid'";
            } else {
                $CouponPost = $lastrow['CouponPostDelete'] - $this->POSTCOUPONSCORE;
                self :: addHongBao(-5, $db);
                $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`CouponPostDelete`='$CouponPost' WHERE UserID='$userid'";
            }

            if (is_object($db)) {
                $rs = $db->query($sql);
            } else {
                $rs = DBQuery :: instance()->executeQuery($sql);
            }
        }

        if ($operatetype == "OTHER") { //�Զ���ɾ�����Ӻ��

            self :: addHongBao($score/100, $db);
            $score = $score;
            $OtherScore = $lastrow['OtherScore'] + $score;
            $sql = "UPDATE UserScoreStatus SET `UpdateTime`=NOW(),`OtherScore`='$OtherScore' WHERE UserID='$userid'";
            $rs = DBQuery :: instance()->executeQuery($sql);

        }

        return true;
    }

}
?>