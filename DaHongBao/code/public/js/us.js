$(function(){
	//用户登录信息
	var member = $('#toplinkcontent');
	if (member.size() > 0) {
		var content = '';
		if ($.cookie('AuthKey') && $.cookie('UserName')) {
			content = '欢迎光临 '+UserName+' ！您现在有 <strong>'+RankPoints+'</strong> 积分&nbsp;&nbsp;&nbsp;|';
			content += '<a href="'+__USER_DOMAIN+'">个人中心</a>|';
			content += '<a href="'+__USER_DOMAIN+'/userpoint">我的积分</a>|';
			content += '<a href="'+__USER_DOMAIN+'/auth/logout?redirect='+redirect+'">退出</a>|';
		} else {
		content += '<a href="'+__USER_DOMAIN+'/auth/register?redirect='+redirect+'" title="注册">注册</a>|';
		content += '<a href="'+__USER_DOMAIN+'/auth/login?redirect='+redirect+'" title="登录">登录</a>|';
		}
		member.html(content+member.html());
		member.show();
	}

	//下拉搜索
	$('#selectcate').hover(
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() {
				$($this).addClass('selectcatehover');
			}, delay);
		},
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() {
				$($this).removeClass('selectcatehover');
			}, delay);
		}
	);
	
	//全部优惠券分类
	$('#nav').hover(
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() { 
				$($this).find('div.allcate > a').addClass('hover');
				$($this).children('ul').removeClass('disn');
			}, delay);
		},
		function(){
			var $this = this
			allCateTimer = setTimeout(function() {
				$($this).find('div.allcate > a').removeClass('hover');
				$($this).children('ul').addClass('disn');
				//兼容IE6显示所有select 元素
				$("select.menuVisible").each(function() {
				  if ($(this).css("visibility") == 'hidden') {
					$(this).removeClass('menuVisible').css('visibility', 'visible');
				  }
				});
			}, delay);
		}
	);
	
	//商家信息展开收起
	$('a.unfold').click(function(){
		$(this).parent('p').hide();
		$(this).parent('p').next().show('slow');
		return false;
	});
	$('a.fold').click(function(){
		$(this).parent('p').hide('slow');
		$(this).parent('p').prev().show();
		return false;
	});

	//向上按钮
	var size = ___getPageSize();
    var position = ___getPageScroll();
    var h = $(".gototop").height()+106;

    var w = $(".gototop").width();
    var mainOffset = $("#main").offset();
    var mainRight = $("#main").width() + mainOffset.left;
    var gototopLeft = mainRight + 26;
    if(size[2] < mainRight + w + 26){
        gototopLeft = size[2] - w;
    }

    $(".gototop").css({
        top:size[3]+position[1]-h,
        left:gototopLeft
    }).hide();
    
    if(position[1] > 20){
        $(".gototop").show();
    }
    
    $(window).resize(function(){
        var position = ___getPageScroll();
        mainOffset = $("#main").offset();
        mainRight = $("#main").width() + mainOffset.left;
        size = ___getPageSize();
        
        if(size[2] < mainRight + w + 26){
            gototopLeft = size[2] - w;
            $(".gototop").css({
                top:size[3]+position[1]-h,
                left:gototopLeft
            });
        }else{
            gototopLeft = mainRight + 26;
            $(".gototop").css({
                top:size[3]+position[1]-h,
                left:gototopLeft
            });
        }
        
    });
    
    $(window).scroll(function(){
        position = ___getPageScroll();
        if(position[1] > 20){
            $(".gototop").css({
                top:size[3]+position[1]-h
            });
            $(".gototop").show();
        }else{
            $(".gototop").hide();
        }
        
        if(position[0] > 0){
            gototopLeft = size[2] + position[0] - w;
            $(".gototop").css({
                left:gototopLeft
            })
        }
    })
})

function showSearchCouponHtml(active) {
	var className = "";
	if (active) {
		className = "active";
	}
	return '<li class="'+className+'" onclick="insertSearchType(\'coupon\')"><a href="#">优惠券</a></li>';
}

function showSearchDealsHtml(active) {
	var className = "";
	if (active) {
		className = "active";
	}
	return '<li class="'+className+'" onclick="insertSearchType(\'deals\')"><a href="#">促 销</a></li>';
}

function insertSearchType(type) {
	var html = "";
	if (type == 'deals') {
		html += showSearchDealsHtml(true);
		html += showSearchCouponHtml();
		$('#searchType').val('deals');
	} else {
		html += showSearchCouponHtml(true);
		html += showSearchDealsHtml();
		$('#searchType').val('coupon');
	}
	$('#selectcate').html(html);
	$('#selectcate').removeClass('selectcatehover');
}

//搜索框，输入值判断
function dhb_searchTextOnfocus(obj) {
	if (obj.value == '美国海淘，轻松购物享优惠...')
		obj.value = '';
}
function dhb_searchTextOnSubmit(formid) {
	var form = document.getElementById(formid);
	if (form.q.value == '' || form.q.value == '美国海淘，轻松购物享优惠...')
		return false;
}
function dhb_searchTextOnBlur(obj) {
	if (obj.value == '') {
		obj.value = '美国海淘，轻松购物享优惠...';
	}
}

function updateHeight(obj1, obj2) {
	var height = $('#'+obj1+' > .couponcol').height();
	if (height < 130) {
		height = 130;
	}
	/*
	var height2 = $('#'+obj2).height();
	if (height1 >= height2) {
		var height = height1;
	} else {
		var height = height2;
	}
	height = height + 14;
	$('#'+obj1).css({'height':height+'px', 'overflow':'hidden'});
	*/
	//height = height + 14;
	$('#'+obj1).css({'height':height+'px', 'overflow':'hidden'});
	$('#'+obj2).css({'height':height+'px', 'overflow':'hidden'});
}

//首页优惠券商家鼠标事件
function showIndexCouponMerchant(obj, merid) {
	$(obj).addClass('mlogohover');
	$('#indexCoupon_'+merid).addClass('disn');
	$('#indexCouponMerchant_'+merid).removeClass('disn');
}

function hideIndexCouponMerchant(obj, merid) {
	$(obj).removeClass('mlogohover');
	$('#indexCoupon_'+merid).removeClass('disn');
	$('#indexCouponMerchant_'+merid).addClass('disn');
}

//copy 优惠券
$(function(){
	var tiptime = 0,oc,om=[],mu,cu,mer,cou,wh,isFlashWork = false;
	function setO(coupon){
		mer = coupon.attr('OfferUrl') || '';
		if(mer){
			var _uri=[];
			mu = base64decode(mer);
			wh = coupon.height()+273;

			var q=412,s = window.screenX || window.screenLeft, t = window.outerWidth || document.documentElement.clientWidth + 40, u = s + t, v = window.screenY || window.screenTop, w = v + 20, x;
			t < screen.width ? u + 80 < screen.width ? x = u - q + 80 : s >= 80 ? x = s - 80 : x = u - q - 20 : x = u - q - 20;
			//$.browser.msie?window.location=mu:!om[mer] || om[mer].closed?om[mer]=window.open(mu):om[mer].location=mu,window.focus();
			if ($.browser.msie) {
				var a=document.createElement("a"); 
				a.href=mu; 
				a.target="_blank"; 
				document.body.appendChild(a); 
				a.click() 
			} else {
				!om[mer] || om[mer].closed?om[mer]=window.open(mu):om[mer].location=mu,window.focus();
			}
		}
	}

	if($.browser.version==6.0){
		$('.copy, .use, .code').bind("click",function(){
			var obj = null;
			var isOpenWindow = true;
			var thisClass = $(this).attr('class');
			if (thisClass == 'copy') {
				isOpenWindow = false;
			}
			var code = $(this).parents(".couponcode").find(".code").text();
			obj = $(this);
			window.clipboardData.setData("Text",code);
			obj.parents(".couponcol").find(".copycodecow").show();
			//5秒后消失提示Tig
			setTimeout(function() {
				obj.parents(".couponcol").find(".copycodecow").hide();
			}, 1000);
			if (isOpenWindow) {
				setO($(this).parents('.couponcol').find(".coupon"));
			}
		});
	} else {
		ZeroClipboard.setMoviePath('/flash/ZeroClipboard.swf');
		$('.copy, .use, .code').mouseover( function() {
			/*
			var isOpenWindow = true;
			var thisClass = $(this).attr('class');
			if (thisClass == 'copy') {
				isOpenWindow = false;
			}
			*/
			if (typeof clip == 'undefined') {
				clip = new ZeroClipboard.Client();
				clip.setHandCursor(true);
			}
			obj = $(this);

			//var docuemntWidth = $(this).width();
			//var docuemntHeight = $(this).height();
			/*
			if (thisClass == 'code') {
				docuemntWidth = docuemntWidth + 8;
				docuemntHeight = docuemntHeight + 8;
			}
			*/
			if (clip.div) {//已创建过包含flash的父层div,则鼠标hover时重新定位flash层的位置
				//clip.receiveEvent('mouseout', null);
				clip.reposition(this);
				$(clip.div).css({overflow:'hidden'});
				//$(clip.div).css({'width':docuemntWidth+'px', 'height':docuemntHeight+'px'});
			} else {
				clip.glue(this);
			};
			if(clip.movie){
				//clip.movie.width=docuemntWidth;
				//clip.movie.height='100%';
			}
			//clip.receiveEvent('mouseover', null);
			clip.addEventListener('mousedown', function(client) {
				isFlashWork = true;
				var code = obj.parents(".couponcode").find(".code").text();
				clip.setText(code);
			});
			clip.addEventListener('complete', function(client){
				if(isFlashWork) {
					var thisClass = obj.attr('class').replace(" hover", "");
					var showObj = obj.parents(".couponcol").find(".copycodecow");
					showObj.show();
					//5秒后消失提示Tig
					setTimeout(function() {showObj.hide();}, 5000);
					if (thisClass != 'copy') {
						setO(obj.parents('.couponcol').find(".coupon"));
					}
					isFlashWork = false;
				}
			});
		});
	}
});

// 邮箱订阅
function email_searchTextOnfocus(obj) {
	if (obj.value == '请输入正确的邮箱地址...')
		obj.value = '';
}
function email_searchTextOnBlur(obj) {
	if (obj.value == '') {
		obj.value = '请输入正确的邮箱地址...';
	}
}
function subcription() {
	var value = $('#emailsubcription').val();
	if (value == '' || value == '请输入正确的邮箱地址...') {
		return false;
	}
	var isvalid = $.trim(value).match(/\b(^(\S+@).+((\.com)|(\.net)|(\.biz)|(\.tv)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\..{2,2}))$)\b/gi);
	if(!isvalid){
		alert('请输入正确的邮箱地址！');
		return false;
	}
	$.ajax({
		type: "POST",
		url : "/ajax?type=subcription&email="+value,
		dataType :"json",
		success: function(result){
			alert(result);
			$('#emailsubcription').val('请输入正确的邮箱地址...');
		}
	});
}

//收藏优惠券
function favorite(couponid, obj) {
	if (!couponid) {
		alert('couponid 不能为空');
		return false;
	}
	if ($.cookie('AuthKey') && $.cookie('UserName')) {
		$.ajax({
			type: "POST",
			url : "/ajax?type=favorite&couponid="+couponid,
			dataType :"json",
			success: function(result){
				if(result.status == 'error') {
					alert(result.msg)
				}else{
					$(obj).replaceWith('<a href="javascript:;" onclick="return false;" class="savedcoupon">已收藏</a>');
				}
			}
		});
	}else{
		window.location.href = __USER_DOMAIN+'/auth/login?redirect='+redirect;
	}
}
