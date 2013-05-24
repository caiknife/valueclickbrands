$(function(){
	//用户登录信息
	var member = $('#userLink');
	if (member.size() > 0) {
		var content = '<ul>';
		if ($.cookie('AuthKey') && $.cookie('UserName')) {
			content += '<li>欢迎光临 '+UserName+' ！您现在有 <strong>'+RankPoints+'</strong> 积分</a> <span>|</span></li>';
			content += '<li><a href="'+__USER_DOMAIN+'">个人中心</a> <span>|</span></li>';
			content += '<li><a href="'+__USER_DOMAIN+'/userpoint">我的积分</a> <span>|</span></li>';
			content += '<li><a href="'+__USER_DOMAIN+'/auth/logout?redirect='+redirect+'">退出</a> <span>|</span></li>';
		} else {
			content += '<li><a href="'+__USER_DOMAIN+'/auth/login?redirect='+redirect+'" title="用户登录">用户登录</a> <span>|</span></li>';
			content += '<li><a href="'+__USER_DOMAIN+'/auth/register?redirect='+redirect+'" title="免费注册">免费注册</a> <span>|</span></li>';
		}
		content += '<li><a target="_blank" href="/article-11/">订阅优惠券</a></li>';
		content += '</ul>';
		member.html(content+member.html());
		member.show();
	};

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
	$('#allcategory').hover(
		function(){
			var $this = this;
			//allCateTimer = setTimeout(function() { 
				$($this).find('div.subChannel > a').addClass('hover');
				$($this).children('ul').css('display', '');
				$($this).addClass('channelSelect');
			//}, delay);
		},
		function(){
			var $this = this;
			//allCateTimer = setTimeout(function() {
				$($this).find('div.subChannel > a').removeClass('hover');
				$($this).children('ul').css('display', 'none');
				$($this).removeClass('channelSelect');
				//兼容IE6显示所有select 元素
				$("select.menuVisible").each(function() {
				  if ($(this).css("visibility") == 'hidden') {
					$(this).removeClass('menuVisible').css('visibility', 'visible');
				  }
				});
			//}, delay);
		}
	);
	
	//立即领取剪刀背景
	$(".coupon").hover(
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() { 
				$($this).addClass("hoverRed");
			}, delay);
		},
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() {
				$($this).removeClass("hoverRed");
			}, delay);
		}
	);

	//优惠券排序
	$(".sortBy").hover(
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() { 
				$($this).find('div.sortByList > ul').show();
			}, delay);
		},
		function(){
			var $this = this;
			allCateTimer = setTimeout(function() {
				$($this).find('div.sortByList > ul').hide();
			}, delay);
		}
	);

	//向上按钮
	var size = ___getPageSize();
    var position = ___getPageScroll();
    var h = $(".gototop").height()+106;

    var w = $(".gototop").width();
    var mainOffset = $(".header").offset();
    var mainRight = $(".header").width() + mainOffset.left;
    var gototopLeft = mainRight + 26;
    if(size[2] < mainRight + w + 26){
        gototopLeft = size[2] - w - 3;
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
        mainOffset = $(".header").offset();
        mainRight = $(".header").width() + mainOffset.left;
        size = ___getPageSize();
        
        if(size[2] < mainRight + w + 26){
            gototopLeft = size[2] - w - 3;
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

//搜索框，输入值判断
function dhb_searchTextOnfocus(obj) {
	if (obj.value == '请输入商城名或活动名，如：京东，满200减100')
		obj.value = '';
}
function dhb_searchTextOnSubmit(formid) {
	var form = document.getElementById(formid);
	if (form.q.value == '' || form.q.value == '请输入商城名或活动名，如：京东，满200减100')
		return false;
}
function dhb_searchTextOnBlur(obj) {
	if (obj.value == '') {
		obj.value = '请输入商城名或活动名，如：京东，满200减100';
	}
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

// 邮箱订阅
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

// 收藏优惠券
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
					$(obj).replaceWith('<a href="javascript:;" onclick="return false;" class="collectioned">已收藏</a>');
				}
			}
		});
	}else{
		window.location.href = __USER_DOMAIN+'/auth/login?redirect='+redirect;
	}
}

//领取优惠券
function getCouponCode(couponid, obj, className) {
	if (!couponid) {
		alert('couponid 不能为空');
		return false;
	}
	if ($.cookie('AuthKey') && $.cookie('UserName')) {
		$.ajax({
			type: "POST",
			url : "/ajax?type=getcoupon&couponid="+couponid,
			dataType :"json",
			success: function(result){
				alert(result.msg)
				if(result.status == 'success') {
					var classhtml = ""
					if (className) {
						classhtml = 'class="'+className+'"';
					}
					$(obj).replaceWith('<a href="javascript:;" onclick="return false;" '+classhtml+'>您已领取</a>');
				}
			}
		});
	}else{
		window.location.href = __USER_DOMAIN+'/auth/login?redirect='+redirect;
	}
}

