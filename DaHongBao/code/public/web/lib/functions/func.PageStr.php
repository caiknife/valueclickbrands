<?PHP
function getPageStrs($allnum, $perpage, $bn, $perName, $perID, $itemstr) {
	$newbn = $bn + $perpage;
	$allpage = ceil($allnum / $perpage);
	$curpage = ceil($newbn / $perpage);
	//$this->curPage = $curpage;
	//$this->perPage = $perpage;
	// $this->totalPage = $allpage;
	// $this->totalRow = $allnum;
	//$this->url = $thisurl;
	//$this->itemStr = $itemstr;
	$URLHead = $perName."--".$perID;
	if ($allpage > 10) {
		//{{
		if ($curpage <= 10) {
			if ($curpage == 10) {
				$frompx = 10;
			} else {
				$frompx = 1;
			}

			$topx = $frompx +10;
			$needmore = 1;
			if($topx>$allpage){
				$needmore = 0;
				$topx = $allpage;	
			}
			
			if ($curpage == 10) {
				$PageURL = $perName.".html";
				$eachpage .= "<A href='".$PageURL."' class='blue'>[1]</A> ... ";
			}
			for ($px = $frompx; $px <= $topx; $px ++) {
				if ($px > 10 && $curpage < 10) {
					continue;
				}
				if ($curpage != $px) {
					if ($px == 10) {
						$PageURL = $URLHead."-".$px.".html";
						$eachpage .= "<A href='".$PageURL."' class='blue'>[". ($px)."]</A> ";
					} else {
						if ($px == 1) {
							$PageURL = $perName.".html";
						} else {
							$PageURL = $URLHead."-".$px.".html";
						}
						$eachpage .= "<A href='".$PageURL."' class='blue'>[". ($px)."]</A> ";
					}

				} else {
					if ($curpage == 10) {
						$pp = $px -1;
						$PageURL = $URLHead."-".$pp.".html";
						$eachpage .= "<A href='".$PageURL."' class='blue'>[". ($px -1)."]</A> ";
					}
					$eachpage .= "<B>".$px."</B> ";
				}
			}

			if ($curpage != $allpage && $needmore) {
				$PageURL = $URLHead."-".$allpage.".html";
				$eachpage .= "... <A href='".$PageURL."' class='blue'>[".$allpage."]</A> ";
			}
		} else {
			$PageURL = $perName.".html";
			$eachpage .= "<A href='".$PageURL."' class='blue'>[1]</A> ... ";
			if ($curpage > ($allpage -10)) {
				$frompx = $curpage - ($curpage % 10);
				$topx = $allpage;

				if ($curpage == $frompx) {
					$frompx = $frompx -10;
					if (($frompx +11) == $allpage){
						$topx = $frompx +10;
					}else{
						$topx = $frompx +11;
					}
					
				
					if ($topx > $allpage || ($topx+10>$allpage)) {
						$topx = $allpage;
					}
				}

				for ($px = $frompx; $px <= $topx; $px ++) {
					if ($curpage != $px) {
						if ($px == 1) {
							$PageURL = $URLHead.".html";
						} else {
							$PageURL = $URLHead."-".$px.".html";
						}

						if ($px == $frompx && ($curpage % 10 == 0)) {

							$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";

						} else {
							if ($px == $topx)
								$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";

							else
								$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";
						}
					} else {
						$eachpage .= "<B>".$curpage."</B> ";
					}
				}
			} else {

				if ($curpage % 10 == 0) {
					$frompx = $curpage -10;
				} else {
					$frompx = $curpage - ($curpage % 10);
				}

				$topx = $frompx +10;
				for ($px = $frompx; $px <= $topx; $px ++) {
					if ($curpage != $px) {
						$PageURL = $URLHead."-".$px.".html";
						if ($px == $frompx) {
							$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";
						} else {
							$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";
						}
					} else {

						if ($px == $frompx) {
							if ($curpage % 10 == 0) {
								$PageURL = $URLHead."-". ($px -1).".html";
								$eachpage .= "<A href='".$PageURL."' class='blue'>[". ($px -1)."]</A> ";
							}

							$eachpage .= " ".$px." ";
						}
						elseif ($px == $topx) {
							$eachpage .= " ".$px." ";
							if ($curpage % 10 == 0) {
								$PageURL = $URLHead."-". ($px +1).".html";
								$eachpage .= "<A href='".$PageURL."' class='blue'>[". ($px +1)."]</A> ";
							}

						} else {
							$eachpage .= " <B>".$px."</B> ";
						}
					}
				}
				if ($curpage != $allpage) {
					$PageURL = $URLHead."-".$allpage.".html";
					$eachpage .= "... <A href='".$PageURL."' class='blue'>[".$allpage."]</A> ";
				}
			}
		}
	} //}}
	else {
		//{{
		for ($px = 1; $px <= $allpage; $px ++) {
			if ($curpage != $px) {
				if ($px == 1) {
					$PageURL = $perName.".html";
					$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";
				} else {
					$PageURL = $URLHead."-".$px.".html";
					$eachpage .= "<A href='".$PageURL."' class='blue'>[".$px."]</A> ";
				}
			} else {
				$eachpage .= "<B>".$px."</B> ";
			}
		}
	} //}}
	$eachpage = $eachpage;

	$fromrow = $perpage * ($curpage -1);
	//$fromrow = $fromrow;
	$torow = $fromrow + $perpage;

	//kelvin 2004-08-27
	if ($torow > $allnum)
		$torow = $allnum;

	if ($fromrow == 0)
		$fromrow = 1;
	if ($curpage == 1) {
		if ($curpage >= $allpage) {
			$pagestr = $eachpage;
		} else {
			$PageURL = $URLHead."=". ($curpage +1);
			$pagestr = $eachpage;
		}
	} else {
		if ($curpage >= $allpage) {
			$PageURL = $URLHead."=". ($curpage -1);
			$pagestr = $eachpage;
		} else {
			$PageURL = $URLHead."=". ($curpage -1);

			$PageURL2 = $URLHead."=". ($curpage +1);
			$pagestr = $eachpage;
		}
	}
	if ($allpage > 17) {
		//$pagestr = "<br>".$pagestr;
		$pagestr = $pagestr;
	}
	if ($allnum < 1) {
		$pagestr = "no ".$itemstr;
	}

	//$this->fromRow = $fromrow;

	return $pagestr;
}
?>