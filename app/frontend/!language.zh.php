<?php
return array(
/**
 * 语言文件
 * $this->lang->get('key') 获得翻译
 */
    //页面提示的信息
    'global_sexAsMan' => '先生',
    'global_sexAsFemale' => '女士',
    'global_row5' => '5 行',
    'global_row10' => '10 行',
    'global_row20' => '20 行',
    'global_row30' => '30 行',
    'global_row40' => '40 行',
    'global_row50' => '50 行',
    'global_row60' => '60 行',
    'global_row70' => '70 行',
    'global_row80' => '80 行',
    'global_row90' => '90 行',
    'global_row100' => '100 行',
    //地区列表
    'global_areaList' => array(''=>'',
        '安徽' => '安徽',
        '北京' => '北京',
        '重庆' => '重庆',
        '福建' => '福建',
        '甘肃' => '甘肃',
        '广东' => '广东',
        '广西' => '广西',
        '贵州' => '贵州',
        '海南' => '海南',
        '河北' => '河北',
        '黑龙江' => '黑龙江',
        '河南' => '河南',
        '湖北' => '湖北',
        '湖南' => '湖南',
        '江苏' => '江苏',
        '江西' => '江西',
        '吉林' => '吉林',
        '辽宁' => '辽宁',
        '内蒙古' => '内蒙古',
        '宁夏' => '宁夏',
        '青海' => '青海',
        '山东' => '山东',
        '上海' => '上海',
        '山西' => '山西',
        '陕西' => '陕西',
        '四川' => '四川',
        '天津' => '天津',
        '新疆' => '新疆',
        '西藏' => '西藏',
        '云南' => '云南',
        '浙江' => '浙江',
        '香港' => '香港',
        '台湾' => '台湾',
        '澳门' => '澳门',
        '海外' => '海外',
    ),
	//支付方式_住宅、别墅
    'zffs_zzbs'=> array(
		'1'=>'押一付三', 
		'2'=>'押一付二', 
		'3'=>'押一付一', 
		'4'=>'押二付一', 
		'5'=>'半年付', 
		'6'=>'年付',
		'7'=>'面议'
	),
	// 配套设施_住宅
    'ptss_zz'=> array(
		'1'=>'床', 
		'2'=>'宽带', 
		'3'=>'电视', 
		'4'=>'冰箱', 
		'5'=>'洗衣机', 
		'6'=>'空调', 
		'7'=>'热水器', 
		'8'=>'暖气'
	),
	// 配套设施_别墅
    'ptss_bs'=> array(
		'1'=>'车库', 
		'2'=>'露台', 
		'3'=>'游泳池', 
		'4'=>'阁楼', 
		'5'=>'阳光房', 
		'6'=>'空调', 
		'7'=>'车位'
	),
	// 房屋配套_商铺
    'fwpt_sp'=> array(
		'1'=>'客梯', 
		'2'=>'货梯', 
		'3'=>'停车位', 
		'4'=>'暖气', 
		'6'=>'空调', 
		'7'=>'网络'
	),
	// 可经营类别_商铺
    'kjylb_sp'=> array(
		'1'=>'餐饮美食', 
		'2'=>'服饰鞋包', 
		'3'=>'休闲娱乐', 
		'4'=>'美容美发', 
		'5'=>'生活服务', 
		'6'=>'百货超市', 
		'7'=>'酒店宾馆', 
		'8'=>'家居建材',
		'9'=>'其他',
	),
	
	// 类别_商铺
    'lb_sp'=> array(
		'1'=>'商铺出租', 
		'2'=>'商铺转让'
	),
	//商铺类型_商铺
    'splx_sp'=> array(
		'1'=>'住宅底商', 
		'2'=>'商业街商铺', 
		'3'=>'临街门面', 
		'4'=>'写字楼配套底商', 
		'5'=>'购物中心/百货', 
		'6'=>'其他'
	),
	//当前状态_商铺
    'dqzt_sp'=> array(
		'1'=>'营业中', 
		'2'=>'闲置中', 
		'3'=>'新铺'
	),
	//支付方式_商铺
    'zffs_sp'=> array(
		'1'=>'面议', 
		'2'=>'月付', 
		'3'=>'季付', 
		'4'=>'半年付', 
		'5'=>'年付'
	),
	//类型_写字楼
    'lx_xzl'=> array(
		'1'=>'纯写字楼', 
		'2'=>'商住楼', 
		'3'=>'商业综合体楼', 
		'4'=>'酒店写字楼'
	),
	//级别_写字楼
    'jb_xzl'=> array(
		'1'=>'甲级', 
		'2'=>'乙级', 
		'3'=>'丙级'
	),
	//是否包含物业费_写字楼
    'iswyf_xzl'=> array(
		'1'=>'是', 
		'2'=>'否'		
	),
	
	//合租_户型_卧室
    'hz_fx_ws'=> array(
		'1'=>'主卧', 
		'2'=>'次卧', 
		'3'=>'床位', 
		'4'=>'单间'
	),
	//合租_户型_房间数
    'hz_fx_fjs'=> array(
		'1'=>'1', 
		'2'=>'2', 
		'3'=>'3', 
		'4'=>'4',
		'5'=>'5', 
		'6'=>'6', 
		'7'=>'7', 
		'8'=>'8',
		'9'=>'9'
	),
	//合租_户型_性别限制
    'hz_fx_xbxz'=> array(
		'1'=>'性别不限', 
		'2'=>'限男生', 
		'3'=>'限女生', 
		'4'=>'限夫妻'
	),
	//租金单位_写字楼
    'zj_dw_xzl'=> array(
		'1'=>'元/平米·天', 
		'2'=>'元/平米·月', 
		'3'=>'元/月'
	),
	
	//判断是否可分割
    'isfg'=> array(
		'1'=>'可分割', 
		'2'=>'不可分割'		
	),
	//求购楼层写字楼
    'lc_qg_xzl'=> array(
		'1'=>'低区', 
		'2'=>'中区', 
		'3'=>'高区'
	),
	//求购楼层住宅
    'lc_qg_zz'=> array(
		'1'=>'底层', 
		'2'=>'中低层', 
		'3'=>'高层',
		'4'=>'顶层',
		'5'=>'地下室'
	),
	//求购房龄住宅
    'fl_qg_zz'=> array(
		'1'=>'2年以下', 
		'2'=>'2-5年', 
		'3'=>'5-10年',
		'4'=>'10年以上',
	),
	//求租希望户型
    'fx_qz'=> array(
		'1'=>'一居', 
		'2'=>'二居', 
		'3'=>'三居',
		'4'=>'四居',
		'5'=>'五居',
		'6'=>'五居以上',
	),
	
	//证件类型
    'zjlx'=> array(
		'1'=>'身份证', 
		'2'=>'工作证', 
		'3'=>'军官证',
		'4'=>'学生证',
	),
	
	//教育程度
    'jycd'=> array(
		'1'=>'初中', 
		'2'=>'高中', 
		'3'=>'中专',
		'4'=>'大专',
		'5'=>'本科',
		'6'=>'硕士',
		'7'=>'博士',
		'8'=>'其它',
	),
	//职业
    'zy'=> array(
		'1'=>'政府机关/干部', 
		'2'=>'计算机', 
		'3'=>'网络',
		'4'=>'商业/贸易',
		'5'=>'银行/金融/证劵/保险',
		'6'=>'税务',
		'7'=>'咨询',
		'8'=>'服务',
		'9'=>'旅游/饭店',
		'10'=>'房地产',
		'11'=>'法律/司法',
		'12'=>'文化/教育',
		'13'=>'媒介/广告',
		'14'=>'农/渔/林/畜牧业',
		'15'=>'矿业/制造业',
		'16'=>'学生',
		'17'=>'自由职业',
		'18'=>'其他',
	),
/**
 * 分页按钮带默认参数&语言设定
 */
    'pagination' => array(
        'numDisplayEntries' => 8,
        'numEdgeEntries' => 1,
        'linkTo' => '#',
        'prevText' => '上一页',
        'nextText' => '下一页',
        'ellipseText' => '...',
        'prevShowAlways' => true,
        'nextShowAlways' => true,
    ),
	
	//fang
	'fang_createTitle' => '发布房源信息',
	'fang_editTitle' => '编辑房源信息',
	
	
    //Gusetbook
    'guestbook_postError' => '对不起,你提交的数据有错误,留言未能成功提交...',
    'guestbook_postAbortive' => '发生未知错误,留言未能成功写入,请与管理员联系.',
    'guestbook_postSuccessful' => '你的留言提交成功,謝謝您的留言!',
    'guestbook_postGoBackText' => '返回到留言板',
    //Bespeak
    'bespeak_postError' => '对不起,你提交的数据有错误,预约未能成功提交...',
    'bespeak_postAbortive' => '发生未知错误,预约未能成功写入,请与管理员联系.',
    'bespeak_postSuccessful' => '你的预约提交成功,謝謝您的留言!',
    'bespeak_postGoBackText' => '返回到手术预约',
//Page Title
    'article_showlistTitle' => '资讯列表',
    'article_viewTitle' => '资讯内容',
    'product_showlistTitle' => '商品列表',
    'product_viewTitle' => '商品信息',
    'poll_showlistTitle' => '投票列表',
    'poll_viewTitle' => '投票信息',
    'join_formTitle' => '加盟申请',
    'defautl_loginTitle' => '会员登录',
    'guestbook_showlistTitle' => '留言信息',
    'memberNotice_showlistTitle' => '会员公告列表',
    'memberNotice_viewTitle' => '会员公告内容',
//Page Error
    'pegeIsInexistent' => '404错误,你访问的页面不存在.!',
//default
    'default_registTitle' => '会员注册',
    'default_registValueIsImproper' => '错误!非法的状态值!',
    'default_registIdIsEmpty' => '错误!没有指定的ID号!',
    'default_registUsernameIsEmpty' => '用户名不能为空',
    'default_registUsernameIsExist' => '该用户已经存在,请重新填写',
    'default_registStateIsEmpty' => '请指定会员的状态',
    'default_registGroupIsEmpty' => '请指定会员的会员组',
    'default_registEmailIsEmpty' => '电子邮箱不能为空,请重新填写',
    'default_registPhoneIsEmpty' => '联系电话不能为空,请重新填写',
    'default_registAreaIsEmpty' => '请指定所在地区',
    'default_registIntroIsEmpty' => '会员简介不能为空,请重新填写',
    'default_registUserpassIsEmpty' => '会员密码不能为空,请重新填写',
    'default_registCuserpassIsEmpty' => '确认密码不能为空,请重新填写',
    'default_registUserpassUnmatched' => '输入的密码与确认密码不匹配.',
    'default_registInvalidationManipulation' => '操作失败!,原因未知...',
    'default_registSuccessful' => '注册成功!',
    'default_loginUsernameIsEmpty' => '请填写用户名!',
    'default_loginUsernameIsNotExist' => '用户不存在!',
    'default_loginUserpassIsEmpty' => '请填写用户密码!',
    'default_loginStateIsDeleteOrLock' => '用户不存在或被冻结!',
    'default_loginUserpassIncorrectness' => '你输入的密码不正确!',
//Member
    'member_stateEnable' => '已激活',
    'member_stateDisable' => '已冻结',
    'member_stateDelete' => '已删除',
    'member_profileTitle' => '修改会员资料',
    'member_ChangePasswordTitle' => '密码修改',
    'member_ProfileUpdateSucceedMsg' => '修改成功!',
    'member_profileEmailError' => '你输入的email有错误.请重新输入.',
    'member_cpOuserpassIsEmpty' => '旧密码不能为空,请重新填写',
    'member_cpOuserpassIsError' => '旧密码不匹配',
    'member_cpUserpassIsEmpty' => '新密码不能为空,请重新输入.',
    'member_cpCuserpassIsEmpty' => '确认密码不能为空,请重新输入.',
    'member_cpUserpassUnmatched' => '输入的新密码与确认密码不匹配',
    'member_cpUpdateSucceedMsg' => '密码修改成功!',
//ArticleComment
    'comment_createInvalidationManipulation' => '操作失败!,原因未知...',
    'comment_createSuccessful' => '发表评论成功!',
    
);
?>
