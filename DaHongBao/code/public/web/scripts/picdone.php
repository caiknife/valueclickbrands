<?PHP

require_once("../etc/const.inc.php");
require_once(__INCLUDE_ROOT."lib/classes/class.thumb.php");

$t = new ThumbHandler();

$iid = $_GET['id'];

for($i=$iid;$i<100;$i++){
	$dir    = __IMAGE_SRC.'add/'.$i;
	$files1 = scandir($dir);
echo $i."<BR>";
	foreach($files1 as $key=>$value){
		if($key==0 ||$key==1){

		}else{
			$a = explode(".",$value);

			$b = explode("_",$value);
			if (file_exists(__IMAGE_SRC."add/".$i."/".$b[0]."_small.".$a[1])) {

			} else {
			

				$src= __IMAGE_SRC."add/".$i."/".$value;
				$outsrc= __IMAGE_SRC."add/".$i."/".$a[0]."_small.".$a[1];
				
				$t->setSrcImg($src);
				$t->setDstImg($outsrc);
				$t->createImg(70,82);

				//makethumb(,,70,82);
			}
			
		}

	}

}



function makethumb($srcFile,$photo_small,$dstW,$dstH) {
	$data = GetImageSize($srcFile,&$info);
	switch ($data[2]) {
	case 1: //图片类型，1是GIF图
	$im = @ImageCreateFromGIF($srcFile);
	break;
	case 2: //图片类型，2是JPG图
	$im = @imagecreatefromjpeg($srcFile);
	break;
	case 3: //图片类型，3是PNG图
	$im = @ImageCreateFromPNG($srcFile);
	break;
	}
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	$ni=imagecreatetruecolor($dstW,$dstH);
	ImageCopyResized($ni,$im,0,0,0,0,$dstW,$dstH,$srcW,$srcH);
	ImageJpeg($ni,$photo_small);
	//ImageJpeg($ni); //在显示图片时用，把注释取消，可以直接在页面显示出图片。
}
?>