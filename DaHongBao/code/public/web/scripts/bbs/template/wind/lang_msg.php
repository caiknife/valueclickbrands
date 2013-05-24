<?php

$lang = array (

'undefined_action'				=>"非法操作,请返回",
'not_login'						=>"您还没有登录或注册，暂时不能使用此功能!!",
'refresh_limit'					=>"论坛设置:刷新不要快于 $db_refreshtime 秒",
'data_error'					=>"读取数据错误,原因：您要访问的链接无效,可能链接不完整,或数据已被删除!",
'user_not_exists'				=>"用户<b>{$errorname}</b>不存在",
'password_confirm'				=>"两次密码输入不一致，请重新输入",
'password_confirm_fail'			=>"密码验证失败",
'password_change_success'		=>"完成密码修改",
'mail_success'					=>"我们已经发送您的密码到您的注册邮箱,请注意查收!",
'mail_failed'					=>"由于服务器邮件系统配置不正确,邮件发送失败",
'email_error'					=>"您输入的用户名和email地址不符，请重新输入。",
'illegal_tid'					=>"帖子ID非法",
'illegal_email'					=>"信箱没有填写或不符合检查标准，请确认没有错误",
'illegal_OICQ'					=>"OICQ或ICQ号码不正确",
'illegal_username'				=>"此用户名包含不可接受字符或被管理员屏蔽,请选择其它用户名",
'illegal_password'				=>"密码包含不可接受字符,请使用英文和数字",
'illegal_customimg'				=>"非法自定义头像：必须以 http 开头，不可包含此字符'|'，必须在(0-185)*(0-200)的大小范围里",
'illegal_loadimg'				=>"非法头像类型",
'sign_limit'					=>"签名不可超过 $gp_signnum 字节",
'introduce_limit'				=>"自我简介不要超过 500 字节",
'username_limit'				=>"为了避免论坛用户名混乱,用户名中禁止使用大写字母,请使用小写字母",
'username_same'					=>"此用户名已经被注册,请选择其它用户名",
'ban_info1'						=>"你已经被管理员禁言, 禁言开始时间 $s_date ，结束时间 $e_date !",
'ban_info2'						=>"你已经被管理员禁言, 类型为永久禁言!",
'ban_info3'						=>'你已经被管理员强制禁言, 你的其他帐号同时也将被禁言!',
'del_error'						=>"没有选择要删除的选项",
'del_success'					=>"完成删除操作",
'credit_error'					=>"自定义积分ID错误",
'most_online'					=>"状态:发生错误,论坛在线会员数已经达到最大值{$db_onlinelmt},请稍后再来!",
'ip_ban'						=>"您的IP被禁止，不能访问论坛",
'check_error'					=>'认证码不正确或已过期',
'keyword_error'					=>'关键字必须被包含在主题中',

'hack_error'					=>'未安装此插件或此插件无前台显示!',

'bk_credit_change_error'		=>"你要转换的积分大于实际的积分",
'bk_credit_fillin_error'		=>"积分转换值填写不正确！必需为大于零的整数",
'bk_credit_type_error'			=>"银行不支持这种积分转换方式",
'bk_virement_error'				=>"自己无法给自己转帐！",
'bk_no_enough_deposit'			=>"您的存款不够支付转帐和手续费，无法实现转帐！",
'bk_virement_count_error'		=>"转帐数目填写不正确！必需填写大于等于{$bk_virelimit}的整数",
'bk_virement_close'				=>"现在不允许转帐",
'bk_draw_error'					=>"取款金额大于您的存款金额！",
'bk_draw_fillin_error'			=>"取款数目填写不正确！必需填写大于零的整数",
'bk_save_error'					=>"存款金额大于您拥有的金额！",
'bk_save_fillin_error'			=>"存款数据错误！必需输入大于零的整数",
'bk_time_limit'					=>"{$bk_timelimit}秒内不允许重新交易",
'bk_close'						=>"银行被管理员关闭",

'readvote_noright'				=>"对不起，您没有权限查看投票会员",

'upload_group_right'			=>"用户组权限：你所属的用户组没有上传附件的权限",
'upload_forum_right'			=>"对不起，本论坛只有特定用户可以上传附件，请返回",
'upload_close'					=>"附件上传功能已关闭",
'upload_size_error'				=>"附件{$atc_attachment_name}超过指定大小{$db_uploadmaxsize}字节",
'upload_type_error'				=>"附件{$atc_attachment_name}的类型不符合准则",
'upload_num_error'				=>"您今天上传的附件已经达到指定个数($gp_allownum 个)",
'upload_error'					=>"上传附件失败，造成的原因可能有:附件目录不可写(777)、空间在安全模式下、空间大小已不足。",
'upload_content_error'			=>"附件$atc_attachment_name 内容非法,系统已经将其自动删除!",

'reply_ifcheck'					=>"该贴未通过管理员验证，不可回复",
'reply_lockatc'					=>"该贴已被锁定，不可回复",
'reply_group_right'				=>"用户组权限：你所属的用户组没有发表回复的权限!",
'reply_forum_right'				=>"本论坛只有特定用户组才能回复主题,请到其他版块回贴,以提高等级!",

'postnew_group_right'			=>"用户组权限：你所属的用户组没有发表主题的权限!",
'postnew_forum_right'			=>"本论坛只有特定用户组才能发表主题,请到其他版块发贴,以提高等级!",
'postnew_group_vote'			=>"用户组权限：你所属的用户组没有发表投票的权限",
'postnew_group_active'			=>"用户组权限：你所属的用户组没有发起活动的权限",

'modify_noper'					=>"您无权限编辑别人的贴子",
'modify_locked'					=>"该贴已被锁定，不可编辑",
'modify_admin'					=>"您无权编辑管理员或总版主的帖子",
'modify_timelimit'				=>"拒绝用户编辑:您已超过编辑时间限制 $gp_edittime 分钟",
'vote_not_modify'               =>"该投票主题不允许修改投票结果!",
'modify_group_right'			=>"您所在的用户组,没有权限删除自己的帖子",
'modify_replied'				=>"主题已被回复,不能删除",


'postfunc_noempty'				=>"不接受空选项",
'postfunc_money_limit'			=>"出售价格错误,应在0-1000之间",
'postfunc_subject_limit'		=>"标题为空或标题太长(请控制在{$db_titlemax}字节)",
'postfunc_content_limit'		=>"文章长度错误(请控制在{$db_postmin}-{$db_postmax}字节)",
'postfunc_upgrade_error'		=>"无此提升方式",

'msg_refuse'					=>"您发送的消息被用户拒绝",

'forumpw_pwd_error'				=>"密码错误,请重新输入密码",
'forumpw_guest'					=>"游客无权登录加密版块",
'forumpw_needpwd'				=>"本版块为加密版块,需密码验证( 游客无权登录此版块)",

'forum_hidden'					=>"本版块为隐藏版块,您无权进入",
'forum_jiami'					=>"对不起,本版块为认证版块,您没有权限查看此版块的内容",
'forum_former'					=>"本版块为正规版块,只有注册会员才能进入",
'forum_guestlimit'				=>"对不起，本版块只允许注册会员进入",

'sort_group_right'				=>"用户组权限：你所属的用户组不能查看统计与排行",

'sendpwd_limit'					=>"<font color=red>发送失败</font>:请不要在 $gp_postpertime 秒内连续性的使用此功能.",

'sendeamil_subject_limit'		=>"邮件标题为空，请填写",
'sendeamil_content_limit'		=>"请填写邮件内容并在20字节之上",
'sendeamil_limit'				=>"<font color=red>发送失败</font>:不要连续发送邮件.请等候....",
'sendeamil_refused'				=>"用户{$userdb[username]}不接受邮件",

'search_opensch'				=>"由于网站流量的原因, 站点开放搜索时间为 $db_schstart:00 点到 $db_schend:00 点 !",
'no_condition'					=>"请输入搜索条件.",
'search_none'					=>"没有查找匹配的内容",
'search_forum_right'			=>"您无权搜索此版块帖子",
'search_group_right'			=>"用户组权限：你所属的用户组不能使用搜索功能",
'search_limit'					=>"对不起{$gp_searchtime}秒内只能进行一次搜索",
'search_word_limit'				=>"关键字长度要大于2",
'search_cate'					=>"不能搜索分类",
'illegal_keyword'				=>'关键字非法.',
'illegal_author'				=>'用户名非法.',

'reg_email_fail'				=>"帐号需要激活,激活邮件发送失败,请联系管理员!",
'reg_email_success'				=>"您的帐号需要激活,我们已经发送了一封邮件到您的邮箱，请查收!",
'reg_username_limit'			=>"注册名长度错误,请控制在 $rg_regminname - $rg_regmaxname 字节以内",
'reg_repeat'					=>"您已经是注册成员,请不要重复注册!",
'reg_limit'						=>"同一IP{$rg_allowsameip}小时内不能重复注册",
'reg_close'						=>"对不起,目前论坛禁止新用户注册,请返回!",
'reg_jihuo_fail'				=>"激活失败,错误原因:用户名不存在或验证参数有误!",
'reg_jihuo_success'				=>"完成激活密码,您的帐号已经激活,谢谢您的支持",
'reg_reason'					=>'请填写注册原因。',
'reg_refuse'                    =>'您的注册被拒绝,填写正确的防恶意注册答案!',

'read_locked'					=>"此帖被管理员关闭，暂时不能浏览",
'read_check'					=>"这篇帖子还没通过管理员验证，暂时不能查看",
'read_group_right'				=>"用户组权限：你所属的用户组没有浏览帖子的权限",

'pro_custom_fail'				=>"要使用自定义头像功能前提 : 请先删除上传的头像",
'pro_loadimg_fail'				=>"您已经上传过头像，要重新上传头像请先删除原来上传的头像",
'pro_loadimg_close'				=>"头像上传功能已关闭",
'pro_loadimg_right'				=>"用户组权限：你所属的用户组没有上传附件的权限",
'pro_loadimg_limit'				=>"上传的头像超过指定大小$db_imgsize 字节",
'pro_loadimg_sizelimit'			=>"上传的头像必须在(0-$db_imgwidth)*(0-$db_imglen)的大小范围里",
'pro_loadimg_error'				=>"上传的头像错误：非法操作或头像无效!",
'pro_manager'					=>"创始人密码请到后台修改",
'pro_emailcheck'				=>'系统开启了邮件验证系统，不允许更改邮件信息。',

'post_wordsfb'					=>"警告： 您提交的内容中含有不良言语 '<font color='red'>$banword</font>'！",
'post_gp_limit'					=>"用户组权限：你所属的用户组每日最多能发 $_G[postlimit] 篇帖子.",
'post_limit'					=>"灌水预防机制已经打开，在{$gp_postpertime}秒内不能发贴",
'post_newrg_limit'				=>"新注册用户$db_postallowtime 小时内不能发帖！",
'post_check'					=>"您还没通过管理员验证,需要通过管理员验证才能发言！",
'post_openpost'					=>"由于工作力度与时间原因, 站点开放发帖时间为 $db_poststart:00 点到 $db_postend:00 点 !",
'post_vote_only'				=>"投票版块只允许发起投票",
'post_recycle'					=>"本版为回收站，不能发帖",
'vote_num_limit'				=>"投票选项个数超过最大限制（最大：{$db_selcount}个）。",
'notice_illegalid'				=>"无效公告ID",

'msg_ban_fail'					=>"屏蔽列表修改失败",
'msg_ban_success'				=>"完成屏蔽列表修改",
'msg_limit'						=>"<font color=red>发送失败</font>:请不要在 $gp_postpertime 秒内连续性的发送短消息.",
'msg_subject_limit'				=>"标题不得大于75字节,内容不得大于1500字节",
'msg_empty'						=>"用户名，标题或内容为空.",
'msg_group_right'				=>"用户组权限：你所属的用户组没有发送短消息权限",
'msg_error'						=>"该信息已被删除",
'sebox_full'                    =>"您的发件箱容量已满,请删除部分信息.",

'member_right'					=>"用户组权限：你所属的用户组不能查看会员列表",

'mawhole_count'					=>"一次删除，复制或移动帖子不能超过500贴",
'mawhole_error'					=>"不能将帖子复制或移动到分类版块内",
'mawhole_nodata'				=>"没有要删除或移动的帖子数据",
'mawhole_right'					=>"您的等级不足,没有此管理权限",
'mawhole_notype'				=>"该版块没有设置主题分类",

'admin_forum_right'				=>"你无权管理其他版块的帖子",

'masigle_top'					=>"您没有管理分类置顶或总置顶的权限",
'masigle_point'					=>"不能超过您的评分上限，今天剩余评分点数为：<span class=\"s3 b\">{$leavepoint}</span>",
'masigle_nopoint'				=>"您今天的评分点数已经用完，请明天再进行评分操作！",
'masigle_creditlimit'			=>"一次评分不能大于{$maxper},或小于{$minper}",
'masigle_credit_error'			=>"积分点数必须为数字",
'masigle_credit_right'			=>"你无权对此积分进行评分",
'masigle_manager'				=>"除创始人外.不能给自己帖子评分",
'masigle_ban_fail'				=>"禁言失败，{$username}不为会员组，只能禁言会员组",
'member_havebanned'				=>"用户“{$username}”已经被禁言",
'masigle_ban_right'				=>"你没有永久封禁会员的权限",
'masigle_ban_limit'				=>"你能封禁用户的最大天数为{$SYSTEM[banmax]}天",

'login_forbid'					=>"已经连续 6 次密码输入错误,您将在 10 分钟内无法正常登录,还剩余 $L_T 秒",
'login_pwd_error'				=>"密码错误,您还可以尝试 $L_T 次",
'login_jihuo'					=>"你的帐号没有激活，请先到您注册的邮箱里激活帐号!<br /><br />如果您的邮箱无法收到邮件，您可以点击这里，<a href=\"remail.php?uid=$men_uid\"><font color=\"blue\">进行邮件重发!</font></a>",
'login_empty'					=>"用户名或密码为空",
'login_have'					=>"您已经为会员身份,请不要重复登录!",

'job_vote_num'					=>"投票个数超过指定个数",
'job_vote_sel'					=>"没有选择投票项",
'job_havevote'					=>"您已经参与了这次投票,请不要作弊",
'job_vote_lock'					=>"投票失败,帖子已被锁定！",
'job_vote_right'				=>"用户组权限：你所属的用户组没有投票权限",
'job_vote_close'                =>'投票主题已关闭!',
'job_havebuy'					=>"您已经购买此贴.请不要重复!",
'sell_error'					=>"出售价格错误",
'job_buy_noenough'				=>"您的 {$db_moneyname} 不足，您当前拥有 $money {$db_moneyname}，购买此贴需要 {$sellmoney} {$db_moneyname}",
'job_viewtody_close'			=>"后台核心设置关闭统计.需要管理员打开统计才能使用此功能!",
'job_favor_del'					=>"没有指定删除收藏的主题!",
'job_favor_error'				=>"您已经收藏了该主题。",
'job_favor_full'				=>"您的收藏夹已满，收藏夹最大容量为：{$_G[maxfavor]}，请整理收藏夹后再收藏该主题。",
'job_attach_right'				=>"您无权限删除附件",
'job_attach_error'				=>"附件不存在",
'job_attach_rvrc'				=>"您的{$db_rvrcname}小于下载附件所需{$db_rvrcname}，您目前的{$db_rvrcname}：{$userrvrc}，下载该附件需要威望：{$needrvrc}",
'job_attach_group'				=>"用户组权限：你所属的用户组没有下载附件的权限",
'job_attach_forum'				=>"对不起，本论坛只有特定用户可以下载附件，请返回",
'job_delimg_error'				=>"删除失败，您没有使用头像上传功能或上传头像数据错误",

'ip_change'						=>"用户密码已更改 或 站点开启了安全认证 , 需要重新登录!<br><br>如果您无法退出,请点选IE 工具 => 选项 然后手动清除COOKIE",

'no_right'						=>"不能订阅本版文章",

'have_report'					=>"你已经报告过这篇帖子",
'report_success'				=>"完成帖子报告",
'report_right'					=>"用户组权限：你所属的用户组不能使用报告功能",

'profile_right'					=>'用户组权限：你所属的用户组不能查看会员资料',

'no_markright'					=>'用户组权限：你所属的用户组没有评分权限.',
'pingtime_over'                 =>"评分超时，你不能对此帖进行评分!",
'no_markagain'					=>'你已经评过分了，你所属的用户组没有重复评分的权限.',
'member_credit_error'			=>'评分点数错误，请输入一个大于或小于零整数.',

'forum_locked'					=>"该版块设置了锁定 {$forumset[lock]} 天前的帖子，发帖日期超过 {$forumset[lock]} 天的帖子将被锁定，不允许回复。",

'forum_creditlimit'				=>"<table cellspacing=1 cellpadding=3 $i_table><tr><td class=head colspan=2><b>该版块设置了限制积分访问，以下积分为访问该版块需要的最低积分要求</b></td></tr>
									<tr class=f_one><td>积分要求</td><td>您现在的积分</td></tr>
									<tr class=f_one><td>{$db_rvrcname}：{$forumset[rvrcneed]}</td><td>{$db_rvrcname}：{$userrvrc}</td></tr>
									<tr class=f_one><td>{$db_moneyname}：{$forumset[moneyneed]}</td><td>{$db_moneyname}：{$winddb[money]}</td></tr>
									<tr class=f_one><td>{$db_creditname}：{$forumset[creditneed]}</td><td>{$db_creditname}：{$winddb[credit]}</td></tr>
									<tr class=f_one><td>发帖数：{$forumset[postnumneed]}</td><td>发帖数：{$winddb[postnum]}</td></tr>
									</table>",

'unenough_money'				=>"您的{$db_currencyname}不足，不能购买相应的道具，{$db_currencyname}获得途径 <a href=\"userpay.php?action=change\"><font color=\"blue\">{$db_currencyname}转换</font></a> <a href=\"userpay.php\"><font color=\"blue\">{$db_currencyname}充值</font></a>",
'unenough_nums'					=>'您要转让的道具数量大于您拥有的道具数量。',
'unenough_sellnum'				=>'您要购买的道具数量超过了用户出售的道具数量。',
'unenough_toolnum'				=>'您的道具数量不足,无法进行转让操作.',
'unenough_stock'				=>'您要购买的道具数量超过了改道具的系统库存数量。',
'no_stock'						=>'系统库存不足。',
'illegal_nums'					=>'您输入的数字为非法数字，请输入一个大于 “0” 的“整数”。',
'numerics_checkfailed'			=>'您提交的数据中包含非法数据,请返回重新操作.',
'empty_credit'					=>'请填写需要转换的积分。',
'noenough_currency'				=>"您的{$db_currencyname}不足，{$db_currencyname}获得途径 <a href=\"userpay.php?action=change\"><font color=\"blue\">{$db_currencyname}转换</font></a> <a href=\"userpay.php\"><font color=\"blue\">{$db_currencyname}充值</font></a>",
'change_credit_error'			=>'你要转换的积分超过你当前拥有的积分。',
'trade_close'					=>'管理员关闭了用户交易功能。',
'toolcenter_close'				=>'管理员关闭道具中心关闭',

'tool_close'					=>"对不起，该道具未启用，您不能使用该道具。",
'tool_grouplimit'				=>"您所属的用户组不能使用该道具，<a href=\"hack.php?H_name=toolcenter&action=buy&id=$toolid\"><b>点击查看道具详细资料</b></a>。",
'tool_creditlimit'				=>"您的积分没有达到道具的使用积分要求，<a href=\"hack.php?H_name=toolcenter&action=buy&id=$toolid\"><b>点击查看道具详细资料</b></a>。",
'tool_forumlimit'				=>"该版块不能使用道具，<a href=\"hack.php?H_name=toolcenter&action=buy&id=$toolid\"><b>点击查看道具详细资料</b></a>。",
'nothistool'					=>"您的道具箱中没有该道具，您需要到 <a href=\"hack.php?H_name=toolcenter\"><b><i>道具中心</i></b></a> 购买该道具后才能使用",
'tool_authorlimit'				=>"对不起，该道具只对自己发表的帖子有效。",
'tool_error'					=>'使用道具时，传递的参数错误，请返回重试。',

'toolmsg_1_success'				=>"道具使用成功，已经将您的‘{$db_rvrcname}’负分转为 0。",
'toolmsg_1_failed'				=>"道具使用失败，您的‘{$db_rvrcname}’不是负分，不需要转换，您可以在以后有需要时使用此道具。",
'toolmsg_2_success'				=>'道具使用成功，已经将您的所有积分负分都已经转为 0。',
'toolmsg_2_failed'				=>'道具使用失败，您的积分中没有负分，不需要转换，您可以在以后有需要时使用此道具。',
'toolmsg_4_failed'				=>'道具使用失败，您的帖子已经在版块中置顶，不需要对这个帖子使用该道具。',
'toolmsg_5_failed'				=>'道具使用失败，您的帖子已经在分类中置顶，不需要对这个帖子使用该道具。',
'toolmsg_6_failed'				=>'道具使用失败，您的帖子已经在整个论坛置顶，不需要对这个帖子使用该道具。',
'toolmsg_8_success'				=>"道具使用成功，您的用户名已经改为 {$pwuser} 。",

'groupright_show'				=>'用户组权限，你所属的用户组没有使用展区功能。',

'showsign_error'				=>'后台没有开启此功能。',
'showsign_success'				=>'签名展示设置成功。',

'no_tool'						=>"您没有可以使用的道具，请先到 <a href='hack.php?H_name=toolcenter'><font color='blue'>系统交易中心</font></a> 购买道具。",

'username_exists'              => '该用户名已经被注册，请选用其他用户名。',
'username_not_exists'          => '恭喜您，该用户名还未被注册，您可以使用这个用户名注册！',

'colony_close' => "管理员关闭了{$cy_name}功能。",
'colony_reglimit' => "系统不允许注册新的{$cn_name}。",
'colony_groupright' => "用户组权限，你所在的用户组没有创建{$cn_name}的权限。",
'colony_creatfailed' => "您的{$moneyname}不足，创建一个{$cn_name}需要支付 {$cn_createmoney} 个{$moneyname}",
'colony_numlimit' => "您允许创建的{$cn_name}个数已满，每个用户最多只能建{$cn_allowcreate}个{$cn_name}。",
'colony_class' => "请选择{$cn_name}的所属分类。",
'colony_emptyname' => "{$cn_name}的名称不能为空。",
'colony_samename' => "您输入的{$cn_name}名称已经存在，请选择另一个名称。",
'colony_joinlimit' => "加入{$cn_name}失败，您允许加入的{$cn_name}个数已满。",
'colony_memberlimit' => "加入{$cn_name}失败，该{$cn_name}人数已满。",
'colony_joinrefuse' => "该{$cn_name}拒绝新成员加入。",
'colony_joinfail' => "您的{$moneyname}不足，加入该{$cn_name}需要支付 {$cydb[intomoney]} 个{$moneyname}",
'colony_passfail' => "用户 <b>{$rt[username]}</b> {$moneyname}不足，不能通过审核。",
'colony_nocheck' => "您还没通过管理员审核，暂时不能使用{$cn_name}功能。",
'colony_alreadyjoin' => "加入{$cn_name}失败，你已加入了该{$cn_name}。",
'colony_realname' => '真实姓名不能为空，请输入您的真实姓名。',
'colony_samerealname' => '对不起，您输入的真实姓名已经存在，请重新输入。',
'colony_cardright' => "您还不是这个{$cn_name}的会员，不能查看名片夹！",
'colony_editcard' => "您还不是这个{$cn_name}的会员，不能使用名片功能！",
'colony_nocard' => "你要编辑的名片不存在。",
'colony_noseecard' => '您要查看的名片不存在。',
'colony_boardright' => "你不是该{$cn_name}的成员，无权访问该{$cn_name}的讨论区。",
'colony_posterror' => '标题或内容为空。',
'colony_editright' => '你没有权限编辑他人的帖子',
'colony_delright' => "你没有权限删除他人的帖子。",
'colony_donateright' => "你不是该{$cn_name}的成员，不能使用捐献功能。",
'colony_donateerror' => "捐献的{$moneyname}数必须是大于零的整数。",
'colony_donatefail' => "捐献失败，您捐献的{$moneyname}数大于您拥有的{$moneyname}数",
'colony_donatesuccess' => "荣誉点提升成功。",
'colony_quitfail' => "您是该{$cn_name}的创建者，可以使用“解散{$cn_name}”来解散这个{$cn_name}。",
'colony_adminright' => "您不是{$cn_name}管理员，不能使用此功能.",
'colony_sizelimit' => "您上传的图片太大超过了系统限制的大小。",
'colony_uploadfail' => "图片上传失败",
'colony_lenthlimit' => "上传的标志必须在（0 - $cn_imgheight ）*（0 - $cn_imgwidth ）的大小范围里",
'colony_joinmoney' => "会员加入{$cn_name}至少需要{$moneyname}个数：{$cn_joinmoney}",
'colony_addamin' => '管理员设置完成。',
'colony_delladminfail' => "不能取消{$cn_name}创建者的管理员身份。",
'colony_deladmin' => '取消管理员完成。',
'colony_pass' => '审核会员完成。',
'colony_delfail' => "不能删除{$cn_name}创建者。",
'colony_del' => '删除会员完成。',
'colony_cancelclose' => "系统关闭了解散{$cn_name}的功能。",
'colony_cancel' => "您不是{$cn_name}的创建者，无权解散该{$cn_name}.",
'colony_unjoin' => "您还没有加入任何{$cn_name}",
'colony_descrip' => "您必须填写一段{$cn_name}的描述文字",
'colony_phopen' => "相册被系统管理员关闭，暂时无法使用!",
'colony_opentocn' => "此相册只对{$cn_name}内部开放!",
'colony_opentome' => "此相册个人私有，他人无法观看!",
'colony_openlimit' => "本{$cn_name}不对非正式成员开放",
'colony_del_members' => "您必须先删除所有成员，才能解散{$cn_name}",
'colony_del_photo' => "必须删除{$cn_name}下所有相片和相册才能解散",
'colony_viewcard' => "只有正式成员才能查看会员真实资料",
'colony_cnmenber' => "不是本{$cn_name}成员，无法执行此操作",
'colony_creatalbum' => "您不是本{$cn_name}成员，无法在此创建相册",
'colony_aname_empty' => "相册名不能为空",
'colony_pname_empty' => "相片名称不能为空",
'colony_pubalbum' => "您不是管理员，不能创建公共相册",
'colony_moneylimit' => "{$cn_name}没有足够的余额来创建相册",
'colony_album_num' => "相册数量达到最大，无法继续创建",
'colony_album_num2' => "您已经创建了足够多的相册，无法继续创建",
'colony_moneylimit2' => "您没有足够的{$moneyname}创建相册",
'colony_photonum' => "本相册下还存有图片，请先清空图片再删除相册",
'colony_editphoto' => "只有上传者自己才可以编辑照片",
'colony_albumclass' => "请选择相片所属的相册分类",
'colony_phototype' => "此相册为个人相册，您无权上传",
'colony_subject' => "标题长度不能大于50个字节",
'colony_photofull' => "此相册相片数量达到上限，无法上传",
'colony_filetype' => "上传的不是一个图像文件",
'colony_filesize' => "上传图片大小超过限制",
'colony_uploadnull' => "没有选择上传图片",
'colony_ifadmin' => "您无权执行此项操作",
'colony_update' => "该{$cn_name}已经升级过，无法再次升级",
'colony_updatemoney' => "{$cn_name} 资金不足,无法升级.",

'sale_error' => '交易帐号，商品名称，商品价格为空或格式错误！',
'seller_error' => '交易帐号格式错误',

'olpay_seterror'=>'网上支付设置错误，请管理员登录后台“网上支付设置”检查设置是否正确！',
'olpay_numerror'=>"对不起，您输入的金额无效，请输入一个大于{$db_rmblest}的整数。（网络支付最低人民币：{$db_rmblest} 元 ）",
'olpay_paypalerror'=>'贝宝信息设置错误，请管理员登录后台“网上支付设置”检查设置是否正确！',
'olpay_alipayerror'=>'交易信息设置错误，请管理员登录后台“网上支付设置”检查设置是否正确！',
'olpay_pay99error'=>'快钱信息设置错误，请管理员登录后台“网上支付设置”检查设置是否正确！',

'medal_close'=>'系统没有开启勋章功能',
'medal_noreason'=>'请输入操作理由！',
'medal_groupright'=>'您所属的用户组没有颁发（收回）勋章的权限',
'medal_alreadyhave'=>'该用户已经已经拥有此勋章',
'medal_none'=>'该用户没有此勋章',
'medal_dellog'=>'只有管理员才能删除勋章颁发日志',
'medal_nomedal'=>'请选择要授予的勋章！',

'blog_close'=>'系统没有开启博客功能',
'username_empty' => '用户名为空，请填写用户名！',

'colony_cnamelimit'	=> "{$cn_name}名称太长，请控制在 20字节 以内",
'colony_descriplimit'	=> "{$cn_name}简介不符，请控制在 1-255字节之间",
'colony_annoucelimit'	=> "{$cn_name}公告太长，请控制在 255字节 以内",

'virement_success'	=> "{$db_currencyname}转帐成功。",
'empty_password'	=> '请输入验证密码',
'password_error'	=> '密码验证失败，请输入正确的密码',
'virement_closed'	=> "系统没有开启{$db_currencyname}转帐功能",
'currency_limit'	=> "系统设定最低转帐金额为：{$cy_virelimit} {$db_currencyname}",

'realname_limit' => '真实姓名长度错误，请控制在 20字节以内',
'tel_limit' => '电话长度错误，请控制在 15字节以内',
'intro_limit' => '个人简介太长，请控制在 255字节 以内',
'colony_manager'	=> "只有{$cn_name}的创建者有添加和删除管理员的权限",
'colony_currency'	=> "系统没有开启{$cn_name}{$db_currencyname}管理功能",
'colony_noenough_currency'	=> "{$cn_name}的{$db_currencyname}不足，您可以使用捐献功能增加{$cn_name}的{$db_currencyname}",
'no_colony_member'	=> "$pwuser 不是该{$cn_name}的会员，此功能只允许给朋友圈中的会员增加{$db_currencyname}",
'colony_currency_right'=>"只有{$cn_name}创建者有权限管理{$cn_name}的{$db_currencyname}。",

'sendmail_closed'=>"系统没有开启邮件发送功能。",
'no_atclog_right'=>'用户组权限：你所属的用户组不允许使用“查看帖子操作记录”的权限。',
'no_viewright_right'=>'用户组权限：你所属的用户组不允许使用“用户组权限查看”的权限。',
'enterreason'	=> "请输入操作理由。",

'illegal_icon' => "引用头像链接不能超过85个字节",
'friend_already_exists'=>"该用户已经在您的好友列表中。",
'field_lenlimit'=>"您输入的“<b>{$val[title]}</b>”太长，请控制在{$val[maxlen]}字节以内",
'field_empty'=>"“<b>{$val[title]}</b>”为必填内容，请返回填写！",

'special_allowbuy'=>"该用户组身份不允许用户使用“{$db_currencyname}”购买。",
'special_selllimit'=>'您要购买用户组身份的天数小于该用户组“最短购买天数”限制！',
'specialgroup_exists'=>"您已经购买了该用户组身份，用户组身份到期时间：{$enddate}。",
'specialgroup_error'=>'您要购买的用户组不存在。',
'specialgroup_noneed'=>'您已经是该用户组成员，不需要购买！',

'gross_error' => "订单信息错误，请联系管理员解决！",
'passport_close' => "系统没有开启通行证功能。",
'passport_register' => "系统开启通行证功能，请到 <a href=\"$regurl\">注册地址</a> 进行注册!",
'days_limit' => "您的邀请码已过期!",
'invcode_error' => "您要发送的邀请码不存在!",
'group_invite' => "您所在的组别没有购买邀请码的权限!",
'invite_costs' => "您的积分不足以购买邀请码!",
'inv_limitdays'=> "邀请码购买时间限制，请稍侯",

'reward_noright' => "您没有权限管理其他人的悬赏贴!",
'reward_time_limit'=>"悬赏时间没有超过规定时间，不能强制结案!",
'rewardmsg_success'=>"你已经成功地给斑主发送短消息，很快能得到答复!",
'reward_no_forumadmin'=>"对不起，该斑块没有斑主，您的申请无法提交!",
'reward_have_sendmsg'=>"您已经给版主发过消息了，请勿重复发短消息!",
'reward_have'=>"热心助人奖只允许加一次,您已经为该会员加过热心助人奖了!",
'invcode_empty'=>"本站开启了邀请注册功能，没有邀请码将无法正常注册!",
'illegal_invcode'=>"邀请码错误!",
'read_shield'=>"错误操作,该帖已被屏蔽!",
'read_unshield'=>"错误操作,该帖没有被屏蔽!",
'masingle_data_error'=>'链接地址条件不足!',
'remind_data_empty'=>'提示内容不能为空!',
'remind_length'=>'提示内容过长，请删掉一些!',
'medallog_del_error'=>"该勋章未到期，不能删除日志!",
'medal_reason'=>"过期",
'credit_limit'=>"您设置的积分值不能小于系统规定的最小值!",
'reward_credit_limit'=>"您的积分不足，无法进行此操作!",
'reward_credit_error'=>'您的积分类型不合法!',
'reward_help_error'=>"该贴没有剩余的热心助人积分可以奖励",
'reward_helped'=>"该贴已设置过热心助人奖励",
'force_tid_select'=>'本版块开启强制主题分类功能,请选择文章分类!',
'unite_data_error'=>'您输入的数据为空或不是数字!',
'unite_limit'=>'特殊帖无法被合并!',
'unite_fid_error'=>"不同版块的帖子无法被合并!",
'upload_money_limit'=>"您的{$creditname}不足,无法上传附件!",
'download_money_limit'=>"您的{$creditname}不足 {$downloadmoney} ,无法下载附件!",
'time_out'=>'已经超过截止日期，无法申请!',
'num_full'=>'您申请的活动已经达到最大人数，无法再申请了!',
'apply_gender_error'=>'您的性别不符合要求，无法申请!',
'have_act'=>'您已经申请过该次活动了!',
'actid_view_error'=>'您没有参加该次活动或者尚未被批准!',
'selid_illegal'=>'请选择操作对象!',
'active_manager_right'=>'您不是活动发起人，没有管理权限!',
'contact_empty'=>'联系方式不能为空!',
'active_data_empty'=>'必填数据不能为空，请认真填写!',
'deadline_limit'=>'截止日期不得小于当前时间!',
'starttime_limit'=>'开始时间不得小于截止日期!',
'endtime_limit'=>'结束时间不得小于开始时间',
'rightwhere'=>'您没有该版块的管理权限!',
'email_check'=>'为避免无法收到论坛信件，请使用指定邮箱进行注册!',
'remail_error'=>'您还不是本站会员，或者已经通过验证!',
'remail_rgemail_error'=>'您填写的邮箱与注册的邮箱不符，请重新填写!',
'remail_success'=>'邮件重发成功，请查收!',
'not_forumadmin'=>'您不是版主或者不是该版块的版主!',
'inv_close'=>'站点没有开启邀请注册功能!',
'all_credit_error'=>'积分类型错误!',
'credit_enough'=>'您没有足够的积分进行此操作!',
'have_not_showping'=>'错误操作,你没有给该帖评分!',
'sel_error'=>'请选择操作对象!',
'tid_favor_error'=>'该帖已收藏!',
'favor_cate_error'=>'分类名称为空，或者长度超过20个字符!',
'favor_cate_limit'=>'分类名称不能含有 “,” 字符!',
'type_error'=>'你要删除的分类不存在!',
'illegal_request'=>'非法请求，请返回重试!',
'ftp_not_exists'=>'您的 php 不支持 ftp ,请到后台关闭 ftp 功能!',
'content_same'=>'请勿连续发表相同内容的主题!',
'tofriend_msgerror'=>'标题和内容不能为空,请填写!',
'illegal_imgtype'=>'非法图片类型',
'nav_empty_title'=>"标题内容不能为空！",
'nav_empty_link'=>"链接不能为空！",
'pic_not_exists'=>'该图片地址不存在或者没有权限查看该图片！',
'ftp_connect_failed'=>"连接 ftp 服务器失败，请检查：<br />1、服务器地址和端口是否设置正确！ 2、网络是否通畅！",
'ftp_user_failed'=>'ftp 帐号错误！',
'ftp_pass_failed'=>'ftp 密码错误！',
'ftp_cwd_failed'=>'访问文件夹出错，请检查服务器上 “ftp上传目录” 是否已建立！',
);
?>