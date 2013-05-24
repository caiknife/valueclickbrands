<?php

 class AuthNum {
   public static $KEY = "ie@smcn811123";

   /**
    * @功    能:创建图形验证码
    * @开发时间: Aug 18,2008
    */
    public static function doCreateImage($width=70,$height=20,$codeNum=4, $cookieName = 'atnsess') {
        header("Cache-Control: no-cache, must-revalidate");
        Header("Content-type:image/png");
        $atnsess = ''; 
        $str = 'abcdefghijkmnpqrstuvwxyz1234567890'; 
        $l = strlen($str); //得到字串的长度; 

        for($i=1;$i<=$codeNum;$i++) { 
            $num = mt_rand(0,$l-1); 
            $atnsess.= $str[$num]; 
        }
        setcookie($cookieName,md5($atnsess . self::$KEY));
        $im = imagecreatetruecolor($width,$height);//图片宽与高; 
        $bgColor = ImageColorAllocate($im, 255,255,255);
        $fontColor = ImageColorAllocate($im, 0,0,0);
        imagefilledrectangle($im, 0, 0, $width, $height, $bgColor);
        //imagerectangle($im,0,0,$width - 1,$height - 1,$fontColor);
        for($i=0;$i<3;$i++) 
        { 
            $randColor = ImageColorAllocate($im, mt_rand(0, 255),mt_rand(0, 255),mt_rand(0, 255));
            imageline($im,mt_rand(0,$width),mt_rand(0,$height),
                          mt_rand(0,$width),mt_rand(0,$height),$randColor);
        } 
        for($i=0;$i<$codeNum;$i++) {
            imagestring($im,5, $i*$width/$codeNum+mt_rand(1,5),mt_rand(1,3),$atnsess[$i], $fontColor);
        }
        for($i=0;$i<90;$i++)
        { 
            $randColor = ImageColorAllocate($im, mt_rand(50, 255),mt_rand(50, 255),mt_rand(50, 255));
            imagesetpixel($im, mt_rand(0,$width) ,mt_rand(0,$height) , $randColor);
        }
        ImagePNG($im);
        ImageDestroy($im);
    }

    /**
     * @功    能 :验证用户输入的验证码的有效性
     * @开发时间: Aug 18,2008
     */
    public static function doValidate($input, $cookieName = 'atnsess') { 
        $input = trim($input);
        if(!$input) {
            return false;
        }
        $input = md5(strtolower($input) . self::$KEY);
        return (strcmp($_COOKIE[$cookieName],$input) == 0);
    }

 }
?>