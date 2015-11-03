<?php
return array(
/**
 * 语言文件
 * $this->lang->get('key') 获得翻译
 */
    //页面提示的信息
    'global_stateDisabled' => '未发布',
    'global_stateEnabled'  => '已发布',
	'global_stateDisabled_fang' => '未通过审核',
    'global_stateEnabled_fang'  => '审核通过',
    'global_recommendDisabled' => '未推荐',
    'global_recommendEnabled'  => '推荐',
    'global_hotDisabled' => '非热点',
    'global_hotEnabled'  => '热点',
    'global_orderDisabled' => '未置顶',
    'global_orderEnabled'  => '置顶',
    'global_colorDisabled' => '未套红',
    'global_colorEnabled'  => '套红',
    'global_boolY' => '是',
    'global_boolN' => '否',
    'global_stateDelete' => '已删除',
    'global_language_zh' => '中文版',
    'global_language_en' => '英文版',
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
    'global_language' => array(
        'zh' => '简体',
        'tw' => '繁体',
        'en' => '英文',
    ),
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
    
    //医院列表
    'hospital_list'=>array(
    	'1'=>'普瑞眼科',
    	'2'=>'兰州普瑞眼科医院',
    	'3'=>'成都普瑞眼科医院',
    	'4'=>'郑州普瑞眼科医院',
    	'5'=>'昆明普瑞眼科医院',
    	'6'=>'合肥普瑞眼科医院',
    	'7'=>'乌鲁木齐普瑞眼科医院',
    	'8'=>'南昌普瑞眼科医院',
    	'9'=>'上海普瑞眼科医院',
    	'10'=>'重庆普瑞眼科医院',
    	'11'=>'武汉普瑞眼科医院',
    	'12'=>'哈尔滨普瑞眼科医院'
    	),

    // 手术类型
    'bespeak_type'=> array(
		'1'=>'ACL(前房人工晶体)', 
		'2'=>'EPI-LASIK(虹膜定位+波前像差)', 
		'3'=>'标准LASIK(虹膜定位+波前像差)', 
		'4'=>'标准LASEK(虹膜定位+波前像差)', 
		'5'=>'超薄LASIK', 
		'6'=>'EPI-LASIK', 
		'7'=>'标准LASIK', 
		'8'=>'标准LASEK'
		),
		
	// 咨询类型
	'client_type'=> array(
		'1'=>'近视',
	 	'2'=>'白内障', 
	 	'3'=>'眼底病', 
	 	'4'=>'斜弱视', 
	 	'5'=>'青光眼', 
	 	'6'=>'医学验光配镜', 
	 	'7'=>'爱眼护眼', 
	 	'8'=>'其它眼病'
	 	),	
	
    
    //js提示信息
    'j_global_cateSelDisableMsg' => '这个类别是不允许选择的,请选择其它合适的类别...',
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
/**
 * log信息
 */
    'log_article_delete' => '删除文章，操作ID：(%s)。',
    'log_daily_delete' => '删除工作日志，操作ID：(%s)。',
    'log_hospital_delete' => '删除医院信息，操作ID：(%s)。',
    'log_doctor_delete' => '删除医生信息，操作ID：(%s)。',
    'log_equipment_delete' => '删除设备信息，操作ID：(%s)。',
    'log_branch_delete' => '删除科室信息，操作ID：(%s)。',
    'log_guestbook_delete' => '删除留言，操作ID：(%s)。',
    'log_join_delete' => '删除加盟申请表，操作ID：(%s)。',
    'log_member_delete' => '删除会员，操作ID：(%s)。',
    'log_memberGroup_delete' => '删除会员组，操作ID：(%s)。',
    'log_memberNotice_delete' => '删除会员公告，操作ID：(%s)。',
    'log_poll_delete' => '删除投票，操作ID：(%s)。',
    'log_product_delete' => '删除商品，操作ID：(%s)。',
    'log_productOrder_delete' => '删除订单，操作ID：(%s)。',
    'log_systemUser_delete' => '删除系统用户，操作ID：(%s)。',
    'log_systemUserGroup_delete' => '删除系统用户组，操作ID：(%s)。',
    'log_productLogistics_delete' => '删除物流信息，操作ID：(%s)。',
    'log_link_delete' => '删除友情链接，操作ID：(%s)。',
    'log_company_delete' => '删除学友企业，操作ID：(%s)。',
    'log_fund_delete' => '删除项目，操作ID：(%s)。',
    'log_comment_delete' => '删除资讯评论，操作ID：(%s)。',
    'log_album_delete' => '删除相册，操作ID：(%s)。',
    'log_photo_delete' => '删除图片，操作ID：(%s)。',
 /**
 * About
 */
    //页面提示的信息
    'p_about_createTitle' => '发布医院联系方式',
/**
 * zuozhen
 */
    //页面提示的信息
    'p_zuozhen_showlistTitle' => '专家坐诊信息',
    'z_zuozhen_postSuccessful' => '输入成功',
    'z_zuozhen_postAbortive' => '输入失败',
    'p_zuozhen_goBackToList' => '返回专家坐诊表',
/**
 * Guestbook
 */
    //页面提示的信息
    'p_guestbook_showlistTitle' => '留言列表',
    'p_guestbook_editTitle' => '修改留言信息',
    'p_guestbook_replyTitle' => '回复留言',
    'p_guestbook_mulopEnable' => '公开',
    'p_guestbook_mulopDisable' => '不公开',
    'p_guestbook_mulopDelete' => '删除',
    'p_guestbook_IsReply' => '已回复',
    'p_guestbook_NotReply' => '未回复',
    'p_guestbook_goBackToList' => '返回列表',
    //js提示信息
    'j_guestbook_mulopEnable' => '你确定要公开所有的选中的留言吗？',
    'j_guestbook_mulopDisable' => '你确定要不公开所有的选中的留言吗？',
    'j_guestbook_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的留言吗？',
    'j_guestbook_deleteItemMsg' => '你确定要删除该留言吗？',
    //错误提示信息
    'e_guestbook_ValueIsImproper' => '错误!非法的状态值!',
    'e_guestbook_idIsEmpty' => '错误!没有指定的ID号!',
    'e_guestbook_titleIsEmpty' => '主题不能为空',
    'e_guestbook_titleIsExist' => '该主题已经存在,请重新填写',
    'e_guestbook_langIsEmpty' => '请选择该留言的语言类型',
    'e_guestbook_stateIsEmpty' => '请选择留言发布状态',
    'e_guestbook_contentIsEmpty' => '留言内容不能为空',
    'e_guestbook_replyIsEmpty' => '回复内容不能为空',
    'e_guestbook_invalidationManipulation' => '操作失败!,原因未知...',
    'e_guestbook_replyToEmailLost' => '回复到邮箱失败!',
    'e_guestbook_replyLost' => '回复失败!',
    'e_guestbook_replySucceed' => '回复成功!',
    
    /**
 * bespeak
 */
    //页面提示的信息
    'p_bespeak_showlistTitle' => '手术预约列表',
    'p_bespeak_editTitle' => '修改预约信息',
    'p_bespeak_replyTitle' => '回访',
    'p_bespeak_mulopEnable' => '公开',
    'p_bespeak_mulopDisable' => '不公开',
    'p_bespeak_mulopDelete' => '删除',
    'p_bespeak_IsReply' => '已回访',
    'p_bespeak_NotReply' => '未回访',
    'p_bespeak_goBackToList' => '返回列表',
    //js提示信息
    'j_bespeak_mulopEnable' => '你确定要公开所有的选中的预约信息吗？',
    'j_bespeak_mulopDisable' => '你确定要不公开所有的选中的预约信息吗？',
    'j_bespeak_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的预约信息吗？',
    'j_bespeak_deleteItemMsg' => '你确定要删除该预约信息吗？',
    //错误提示信息
    'e_bespeak_ValueIsImproper' => '错误!非法的状态值!',
    'e_bespeak_idIsEmpty' => '错误!没有指定的ID号!',
    'e_bespeak_nameIsEmpty' => '姓名不能为空',
    'e_bespeak_titleIsExist' => '该主题已经存在,请重新填写',
    'e_bespeak_langIsEmpty' => '请选择该预约信息的语言类型',
    'e_bespeak_stateIsEmpty' => '请选择预约信息发布状态',
    'e_bespeak_contentIsEmpty' => '留言内容不能为空',
    'e_bespeak_replyIsEmpty' => '回访内容不能为空',
    'e_bespeak_invalidationManipulation' => '操作失败!,原因未知...',
    'e_bespeak_replyToEmailLost' => '回复到邮箱失败!',
    'e_bespeak_replyLost' => '回访失败!',
    'e_bespeak_replySucceed' => '回访成功!',
    
 /**
 * client
 */
    //页面提示的信息
    'p_client_showlistTitle' => '网上求医列表',
    'p_client_editTitle' => '修改留言',
    'p_client_replyTitle' => '回复留言',
    'p_client_mulopEnable' => '公开',
    'p_client_mulopDisable' => '不公开',
    'p_client_mulopDelete' => '删除',
    'p_client_IsReply' => '已回复',
    'p_client_NotReply' => '未回复',
    'p_client_goBackToList' => '返回列表',
    //js提示信息
    'j_client_mulopEnable' => '你确定要公开所有的选中的留言吗？',
    'j_client_mulopDisable' => '你确定要不公开所有的选中的留言吗？',
    'j_client_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的留言吗？',
    'j_client_deleteItemMsg' => '你确定要删除该留言吗？',
    //错误提示信息
    'e_client_ValueIsImproper' => '错误!非法的状态值!',
    'e_client_idIsEmpty' => '错误!没有指定的ID号!',
    'e_client_nameIsEmpty' => '姓名不能为空',
    'e_client_titleIsExist' => '该主题已经存在,请重新填写',
    'e_client_langIsEmpty' => '请选择该留言的语言类型',
    'e_client_stateIsEmpty' => '请选择留言发布状态',
    'e_client_contentIsEmpty' => '留言内容不能为空',
    'e_client_replyIsEmpty' => '回复内容不能为空',
    'e_client_invalidationManipulation' => '操作失败!,原因未知...',
    'e_client_replyToEmailLost' => '回复到邮箱失败!',
    'e_client_replyLost' => '回复失败!',
    'e_client_replySucceed' => '回复成功!',
    
/** 
 * Login
 */
    //页面提示信息
    'p_login_title' => '登录后台管理',
    //错误提示信息
    'e_login_usernameIsEmpty' => '请填写用户名!',
    'e_login_usernameIsNotExist' => '用户不存在!',
    'e_login_userpassIsEmpty' => '请填写用户密码!',
    'e_login_stateIsDeleteOrLock' => '用户不存在或被冻结!',
    'e_login_userpassIncorrectness' => '你输入的密码不正确!',
/**
 * SystemLog
 */
    //页面提示信息
    'p_systemLog_showlistTitle' => '系统日志记录',
    'p_systemLog_levelNotice' => '消息',
    'p_systemLog_mulopDelete' => '删除',
    'p_systemLog_levelDebug' => '调试',
    'p_systemLog_levelWarning' => '警告',
    'p_systemLog_levelError' => '错误',
    'p_systemLog_levelDatabase' => '数据库',
    //js提示信息
    'j_systemLog_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有选中的日志信息吗？',
    'j_systemLog_deleteItemMsg' => '你确定要删除该日志信息吗？',
    'j_systemLog_clearMsg' => '你确定要清空所有日志信息吗？',
    //错误信息提示
    'e_systemLog_idIsEmpty' => '错误!没有指定的ID号!',
/**
 * DefaultProfile
 */
    //页面提示信息
    'p_default_profileTitle' => '修改个人资料',
    'p_default_changePasswordTitle' => '修改密码',
    'p_default_stateEnable' => '已激活',
    'p_default_stateDisable' => '已冻结',
    'p_default_stateDelete' => '已删除',
    'p_default_updateSucceedMsg' => '资料已更新',
    //错误信息提示
    'e_default_emailError' => '你输入的E-Mail有错误!',
    'e_default_userpassIsEmpty' => '新密码不能为空,请重新填写',
    'e_default_cuserpassIsEmpty' => '确认密码不能为空,请重新填写',
    'e_default_userpassUnmatched' => '输入的新密码与确认密码不匹配',
    'e_default_ouserpassIsEmpty' => '旧密码不能为空,请重新填写',
    'e_default_ouserpassIsError' => '旧密码不匹配',
/**
 * Article
 */
    //页面提示的信息
    'p_article_showlistTitle' => '资讯列表',
    'p_article_createTitle' => '发布资讯',
    'p_article_editTitle' => '修改资讯',
    'p_article_mulopEnable' => '发布',
    'p_article_mulopDisable' => '不发布',
	'p_article_mulopEnable_fang' => '审核通过',
    'p_article_mulopDisable_fang' => '审核不通过',
    'p_article_mulopDelete' => '删除',
    //js提示信息
    'j_article_mulopEnable' => '你确定要发布所有的选中的资讯吗？',
    'j_article_mulopDisable' => '你确定要不发布所有的选中的资讯吗？',
    'j_article_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的资讯吗？',
    'j_article_deleteItemMsg' => '你确定要删除该资讯吗？',
    //错误提示信息
    'e_article_ValueIsImproper' => '错误!非法的状态值!',
    'e_article_idIsEmpty' => '错误!没有指定的ID号!',
    'e_article_titleIsEmpty' => '主题不能为空',
    'e_article_keywordIsEmpty' => '关键词不能为空',
    'e_article_authorIsEmpty' => '文章出处不能为空',
    'e_article_describesIsEmpty' => '简介不能为空',
    'e_article_titleIsExist' => '该主题已经存在,请重新填写',
    'e_article_cateIsEmpty' => '请选择要发布到的类别',
    'e_article_langIsEmpty' => '请选择该资讯的语言类型',
    'e_article_stateIsEmpty' => '请选择资讯发布状态',
    'e_article_contentIsEmpty' => '资讯内容不能为空',
    'e_article_invalidationManipulation' => '操作失败!,原因未知...',

/**
 * fang
 */
 //页面提示的信息
    'p_fang_showlistTitle' => '房源信息列表',
    'p_fang_createTitle' => '发布房源信息',
    'p_fang_editTitle' => '修改房源信息',
    'p_fang_mulopEnable' => '发布',
    'p_fang_mulopDisable' => '不发布',
	'p_fang_mulopEnable_fang' => '审核通过',
    'p_fang_mulopDisable_fang' => '审核不通过',
    'p_fang_mulopDelete' => '删除',
    //js提示信息
    'j_fang_mulopEnable' => '你确定要审核通过所有的选中的房源信息吗？',
    'j_fang_mulopDisable' => '你确定要审核不通过所有的选中的房源信息吗？',
    'j_fang_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的房源信息吗？',
    'j_fang_deleteItemMsg' => '你确定要删除该房源信息吗？',
    //错误提示信息
    'e_fang_ValueIsImproper' => '错误!非法的状态值!',
    'e_fang_idIsEmpty' => '错误!没有指定的ID号!',
    'e_fang_titleIsEmpty' => '主题不能为空',
    'e_fang_keywordIsEmpty' => '关键词不能为空',
    'e_fang_authorIsEmpty' => '文章出处不能为空',
    'e_fang_describesIsEmpty' => '简介不能为空',
    'e_fang_titleIsExist' => '该主题已经存在,请重新填写',
    'e_fang_cateIsEmpty' => '请选择要发布到的类别',
    'e_fang_langIsEmpty' => '请选择该房源信息的语言类型',
    'e_fang_stateIsEmpty' => '请选择房源信息发布状态',
    'e_fang_contentIsEmpty' => '房源信息内容不能为空',
    'e_fang_invalidationManipulation' => '操作失败!,原因未知...',
	
/**
 * Daily
 */
    //页面提示的信息
    'p_daily_showlistTitle' => '工作日志列表',
    'p_daily_createTitle' => '发布工作日志',
    'p_daily_editTitle' => '修改工作日志',
    'p_daily_mulopEnable' => '发布',
    'p_daily_mulopDisable' => '不发布',
    'p_daily_mulopDelete' => '删除',
    //js提示信息
    'j_daily_mulopEnable' => '你确定要发布所有的选中的工作日志吗？',
    'j_daily_mulopDisable' => '你确定要不发布所有的选中的工作日志吗？',
    'j_daily_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的工作日志吗？',
    'j_daily_deleteItemMsg' => '你确定要删除该工作日志吗？',
    //错误提示信息
    'e_daily_ValueIsImproper' => '错误!非法的状态值!',
    'e_daily_idIsEmpty' => '错误!没有指定的ID号!',
    'e_daily_langIsEmpty' => '请选择该工作日志的语言类型',
    'e_daily_stateIsEmpty' => '请选择工作日志发布状态',
    'e_daily_titleIsEmpty' => '标题不能为空',
    'e_daily_contentIsEmpty' => '今日总结不能为空',
    'e_daily_scheduleIsEmpty' => '明日安排不能为空',
    'e_daily_invalidationManipulation' => '操作失败!,原因未知...',
    
 /**
 * notice
 */
    //页面提示的信息
    'p_notice_showlistTitle' => '工作日志列表',
    'p_notice_createTitle' => '发布请假条、申请、通知',
    'p_notice_editTitle' => '修改请假条、申请、通知',
    'p_notice_mulopEnable' => '发布',
    'p_notice_mulopDisable' => '不发布',
    'p_notice_mulopDelete' => '删除',
    //js提示信息
    'j_notice_mulopEnable' => '你确定要发布所有的选中的公文吗？',
    'j_notice_mulopDisable' => '你确定要不发布所有的选中的公文吗？',
    'j_notice_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的公文吗？',
    'j_notice_deleteItemMsg' => '你确定要删除该公文吗？',
    //错误提示信息
    'e_notice_ValueIsImproper' => '错误!非法的状态值!',
    'e_notice_idIsEmpty' => '错误!没有指定的ID号!',
    'e_notice_langIsEmpty' => '请选择该语言类型',
    'e_notice_stateIsEmpty' => '请选择公文发布状态',
    'e_notice_titleIsEmpty' => '标题不能为空',
    'e_notice_contentIsEmpty' => '内容不能为空',
    'e_notice_scheduleIsEmpty' => '内容不能为空',
    'e_notice_invalidationManipulation' => '操作失败!,原因未知...',
    
 /**
 * Hospital
 */
    //页面提示的信息
    'p_hospital_showlistTitle' => '医院列表',
    'p_hospital_createTitle' => '发布医院信息',
    'p_hospital_editTitle' => '修改医院信息',
    'p_hospital_mulopEnable' => '发布',
    'p_hospital_mulopDisable' => '不发布',
    'p_hospital_mulopDelete' => '删除',
    //js提示信息
    'j_hospital_mulopEnable' => '你确定要发布所有的选中的医院信息吗？',
    'j_hospital_mulopDisable' => '你确定要不发布所有的选中的医院信息吗？',
    'j_hospital_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的医院信息吗？',
    'j_hospital_deleteItemMsg' => '你确定要删除该医院信息吗？',
    //错误提示信息
    'e_hospital_ValueIsImproper' => '错误!非法的状态值!',
    'e_hospital_idIsEmpty' => '错误!没有指定的ID号!',
    'e_hospital_nameIsEmpty' => '医院名称不能为空',
    'e_hospital_webIsEmpty' => '网址不能为空',
    'e_hospital_addressIsEmpty' => '地址不能为空',
    'e_hospital_stateIsEmpty' => '请选择发布状态',
    'e_hospital_contentIsEmpty' => '内容不能为空',
    'e_hospital_invalidationManipulation' => '操作失败!,原因未知...',
    
/**
 * Doctor
 */
    //页面提示的信息
    'p_doctor_showlistTitle' => '医生列表',
    'p_doctor_createTitle' => '发布医生信息',
    'p_doctor_editTitle' => '修改医生信息',
    'p_doctor_mulopEnable' => '发布',
    'p_doctor_mulopDisable' => '不发布',
    'p_doctor_mulopDelete' => '删除',
    //js提示信息
    'j_doctor_mulopEnable' => '你确定要发布所有的选中的医生信息吗？',
    'j_doctor_mulopDisable' => '你确定要不发布所有的选中的医生信息吗？',
    'j_doctor_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的医生信息吗？',
    'j_doctor_deleteItemMsg' => '你确定要删除该医生信息吗？',
    //错误提示信息
    'e_doctor_ValueIsImproper' => '错误!非法的状态值!',
    'e_doctor_idIsEmpty' => '错误!没有指定的ID号!',
    'e_doctor_nameIsEmpty' => '医生姓名不能为空',
    'e_doctor_keywordIsEmpty' => '关键词不能为空',
    'e_doctor_dutyIsEmpty' => '医生职位不能为空',
    'e_doctor_rankIsEmpty' => '医生职称不能为空',
    'e_doctor_infoIsEmpty' => '医生简介不能为空',
    'e_doctor_branchIsEmpty' => '请选择科室',
    'e_doctor_hospitalIsEmpty' => '请选择医院',
    'e_doctor_stateIsEmpty' => '请选择发布状态',
    'e_doctor_introIsEmpty' => '医生详细介绍不能为空',
    'e_doctor_invalidationManipulation' => '操作失败!,原因未知...',
    
/**
 * equipment
 */
    //页面提示的信息
    'p_equipment_showlistTitle' => '设备列表',
    'p_equipment_createTitle' => '发布设备信息',
    'p_equipment_editTitle' => '修改设备信息',
    'p_equipment_mulopEnable' => '发布',
    'p_equipment_mulopDisable' => '不发布',
    'p_equipment_mulopDelete' => '删除',
    //js提示信息
    'j_equipment_mulopEnable' => '你确定要发布所有的选中的设备信息吗？',
    'j_equipment_mulopDisable' => '你确定要不发布所有的选中的设备信息吗？',
    'j_equipment_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的设备信息吗？',
    'j_equipment_deleteItemMsg' => '你确定要删除该设备信息吗？',
    //错误提示信息
    'e_equipment_ValueIsImproper' => '错误!非法的状态值!',
    'e_equipment_idIsEmpty' => '错误!没有指定的ID号!',
    'e_equipment_branchIsEmpty' => '请选择科室',
    'e_equipment_nameIsEmpty' => '设备姓名不能为空',
    'e_equipment_keywordIsEmpty' => '关键词不能为空',
    'e_equipment_infoIsEmpty' => '设备简介不能为空',
    'e_equipment_stateIsEmpty' => '请选择发布状态',
    'e_equipment_introIsEmpty' => '内容不能为空',
    'e_equipment_invalidationManipulation' => '操作失败!,原因未知...',

/**
 * comment
 */
    //页面提示的信息
    'p_comment_showlistTitle' => '资讯评论列表',
    'p_comment_editTitle' => '修改评论',
    'p_comment_mulopEnable' => '公开',
    'p_comment_mulopDisable' => '不公开',
    'p_comment_mulopDelete' => '删除',
    //js提示信息
    'j_comment_mulopEnable' => '你确定要公开所有的选中的评论吗？',
    'j_comment_mulopDisable' => '你确定要不公开所有的选中的评论吗？',
    'j_comment_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的评论吗？',
    'j_comment_deleteItemMsg' => '你确定要删除该评论吗？',
    //错误提示信息
    'e_comment_ValueIsImproper' => '错误!非法的状态值!',
    'e_comment_idIsEmpty' => '错误!没有指定的ID号!',
    'e_comment_contentIsEmpty' => '评论内容不能为空',
    'e_comment_invalidationManipulation' => '操作失败!,原因未知...',
    
/**
 * Fund
 */
    //页面提示的信息
    'p_fund_showlistTitle' => '项目列表',
    'p_fund_createTitle' => '发布项目',
    'p_fund_editTitle' => '修改项目',
    'p_fund_mulopEnable' => '发布',
    'p_fund_mulopDisable' => '不发布',
    'p_fund_mulopDelete' => '删除',
    //js提示信息
    'j_fund_mulopEnable' => '你确定要发布所有的选中的项目吗？',
    'j_fund_mulopDisable' => '你确定要不发布所有的选中的项目吗？',
    'j_fund_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的项目吗？',
    'j_fund_deleteItemMsg' => '你确定要删除该项目吗？',
    //错误提示信息
    'e_fund_ValueIsImproper' => '错误!非法的状态值!',
    'e_fund_idIsEmpty' => '错误!没有指定的ID号!',
    'e_fund_titleIsEmpty' => '项目主题不能为空',
    'e_fund_titleIsExist' => '该项目主题已经存在,请重新填写',
    'e_fund_cateIsEmpty' => '请选择要发布到的类别',
    'e_fund_langIsEmpty' => '请选择该项目的语言类型',
    'e_fund_stateIsEmpty' => '请选择项目发布状态',
    'e_fund_contentIsEmpty' => '项目内容不能为空',
    'e_fund_invalidationManipulation' => '操作失败!,原因未知...',

/**
 * gbook
 */
    //页面提示的信息
	'e_gbook_answerIsEmpty'=>'回复内容不能为空',
    'p_gbook_editTitle' => '回复留言',
    'p_gbook_mulopDelete' => '删除',
    //js提示信息
    'j_gbook_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的留言吗？',
    'j_gbook_deleteItemMsg' => '你确定要删除该留言吗？',
    
    
/**
 * Album/photo
 */
    //页面提示的信息
    'p_album_showlistTitle' => '图片文章列表',
    'p_album_createTitle' => '添加图片文章',
    'p_album_mulopEnable' => '通过',
    'p_album_mulopDisable' => '禁止',
    'p_album_mulopDelete' => '删除',
    //js提示信息
    'j_album_mulopEnable' => '你确定要发布所有的选中的图片文章吗？',
    'j_album_mulopDisable' => '你确定要禁止所有的选中的图片文章吗？',
    'j_album_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的图片文章吗？',
    'j_album_deleteItemMsg' => '你确定要删除该图片文章吗？',
    //错误提示信息
    'e_album_ValueIsImproper' => '错误!非法的状态值!',
    'e_album_idIsEmpty' => '错误!没有指定的ID号!',
    'e_album_titleIsEmpty' => '图片文章标题不能为空',
    'e_album_stateIsEmpty' => '请选择图片文章发布状态',
    'e_album_invalidationManipulation' => '操作失败!,原因未知...',
/**
 * photo
 */
    //页面提示的信息
    'p_photo_showlistTitle' => '相片列表',
    'p_photo_mulopEnable' => '通过',
    'p_photo_mulopDisable' => '禁止',
    'p_photo_mulopDelete' => '删除',
    //js提示信息
    'j_photo_mulopEnable' => '你确定要发布所有的选中的相片吗？',
    'j_photo_mulopDisable' => '你确定要禁止所有的选中的相片吗？',
    'j_photo_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的相片吗？',
    'j_photo_deleteItemMsg' => '你确定要删除该相片吗？',

/**
 * link
 */
    //页面提示的信息
    'p_link_showlistTitle' => '友情链接列表',
    'p_link_createTitle' => '发布友情链接',
    'p_link_editTitle' => '修改友情链接',
    'p_link_mulopEnable' => '发布',
    'p_link_mulopDisable' => '不发布',
    'p_link_mulopDelete' => '删除',
    //js提示信息
    'j_link_mulopEnable' => '你确定要发布所有的选中的友情链接吗？',
    'j_link_mulopDisable' => '你确定要不发布所有的选中的友情链接吗？',
    'j_link_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的友情链接吗？',
    'j_link_deleteItemMsg' => '你确定要删除该友情链接吗？',
    //错误提示信息
    'e_link_ValueIsImproper' => '错误!非法的状态值!',
    'e_link_idIsEmpty' => '错误!没有指定的ID号!',
    'e_link_titleIsEmpty' => '网站名称不能为空',
    'e_link_titleIsExist' => '该网站名称已经存在,请重新填写',
    'e_link_invalidationManipulation' => '操作失败!,原因未知...',
    'e_link_urlIsEmpty' => '链接地址不能为空',
    'e_link_logoIsEmpty' => 'logo不能为空',

/**
 * adversite
 */
    //页面提示的信息
    'p_adversite_showlistTitle' => '广告位信息列表',
    'p_adversite_createTitle' => '发布广告位信息',
    'p_adversite_editTitle' => '修改广告位信息',
    'p_adversite_mulopEnable' => '发布',
    'p_adversite_mulopDisable' => '不发布',
    'p_adversite_mulopDelete' => '删除',
    //js提示信息
    'j_adversite_mulopEnable' => '你确定要发布所有的选中的广告位信息吗？',
    'j_adversite_mulopDisable' => '你确定要不发布所有的选中的广告位信息吗？',
    'j_adversite_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的广告位信息吗？',
    'j_adversite_deleteItemMsg' => '你确定要删除该广告位信息吗？',
    //错误提示信息
    'e_adversite_ValueIsImproper' => '错误!非法的状态值!',
    'e_adversite_idIsEmpty' => '错误!没有指定的ID号!',
    'e_adversite_signIsEmpty' => '广告位标记不能为空',
    'e_adversite_titleIsEmpty' => '广告位标题不能为空',
    'e_adversite_signIsExist' => '该广告位标记已经存在,请重新填写',
    'e_adversite_invalidationManipulation' => '操作失败!,原因未知...',
    'e_adversite_urlIsEmpty' => '链接地址不能为空',
    'e_adversite_logoIsEmpty' => 'logo不能为空',    
/**
 * video
 */
    //页面提示的信息
    'p_video_showlistTitle' => '视频信息列表',
    'p_video_createTitle' => '发布视频信息',
    'p_video_editTitle' => '修改视频信息',
    'p_video_mulopEnable' => '发布',
    'p_video_mulopDisable' => '不发布',
    'p_video_mulopDelete' => '删除',
    //js提示信息
    'j_video_mulopEnable' => '你确定要发布所有的选中的视频信息吗？',
    'j_video_mulopDisable' => '你确定要不发布所有的选中的视频信息吗？',
    'j_video_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的视频信息吗？',
    'j_video_deleteItemMsg' => '你确定要删除该视频信息吗？',
    //错误提示信息
    'e_video_ValueIsImproper' => '错误!非法的状态值!',
    'e_video_idIsEmpty' => '错误!没有指定的ID号!',
    'e_video_titleIsEmpty' => '视频标题不能为空',
    'e_video_titleIsExist' => '该视频标记已经存在,请重新填写',
    'e_video_invalidationManipulation' => '操作失败!,原因未知...',
    'e_video_urlIsEmpty' => '链接地址不能为空',
    'e_video_logoIsEmpty' => 'logo不能为空',   
/**
 * ad
 */
    //页面提示的信息
    'p_ad_showlistTitle' => '滚动广告位信息列表',
    'p_ad_createTitle' => '发布滚动广告位信息',
    'p_ad_editTitle' => '修改滚动广告位信息',
    'p_ad_mulopEnable' => '发布',
    'p_ad_mulopDisable' => '不发布',
    'p_ad_mulopDelete' => '删除',
    //js提示信息
    'j_ad_mulopEnable' => '你确定要发布所有的选中的滚动广告位信息吗？',
    'j_ad_mulopDisable' => '你确定要不发布所有的选中的滚动广告位信息吗？',
    'j_ad_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的滚动广告位信息吗？',
    'j_ad_deleteItemMsg' => '你确定要删除该滚动广告位信息吗？',
    //错误提示信息
    'e_ad_ValueIsImproper' => '错误!非法的状态值!',
    'e_ad_idIsEmpty' => '错误!没有指定的ID号!',
    'e_ad_titleIsEmpty' => '滚动广告位标题不能为空',
    'e_ad_titleIsExist' => '该广告位已经存在,请重新填写',
    'e_ad_invalidationManipulation' => '操作失败!,原因未知...',
    'e_ad_urlIsEmpty' => '链接地址不能为空',
    'e_ad_logoIsEmpty' => 'logo不能为空',

 /**
 * branch
 */
    //页面提示的信息
    'p_branch_showlistTitle' => '科室资料列表',
    'p_branch_createTitle' => '发布科室资料',
    'p_branch_editTitle' => '修改科室资料',
    'p_branch_mulopEnable' => '发布',
    'p_branch_mulopDisable' => '不发布',
    'p_branch_mulopDelete' => '删除',
    //js提示信息
    'j_branch_mulopEnable' => '你确定要发布所有的选中的科室资料吗？',
    'j_branch_mulopDisable' => '你确定要不发布所有的选中的科室资料吗？',
    'j_branch_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的科室资料吗？',
    'j_branch_deleteItemMsg' => '你确定要删除该科室资料吗？',
    //错误提示信息
    'e_branch_ValueIsImproper' => '错误!非法的状态值!',
    'e_branch_idIsEmpty' => '错误!没有指定的ID号!',
    'e_branch_nameIsEmpty' => '科室名称不能为空',
   
    'e_branch_invalidationManipulation' => '操作失败!,原因未知...',
    'e_branch_synopsisIsEmpty' => '科室描述不能为空',
    'e_branch_contentIsEmpty' => '科室介绍不能为空',

    
/**
 * Member
 */
    //页面提示的信息
    'p_member_showlistTitle' => '会员列表',
    'p_member_createTitle' => '新建会员',
    'p_member_editTitle' => '修改会员资料',
    'p_member_stateDisabled' => '未激活',
    'p_member_stateEnabled' => '已激活',
    'p_member_stateDelete' => '已删除',
    'p_member_mulopEnable' => '激活',
    'p_member_mulopDisable' => '冻结',
    'p_member_mulopDelete' => '删除',
    'p_member_userpassHint' => '密码默认已隐藏,如果不想修改密码则将此处留空',
    //js提示信息
    'j_member_mulopEnable' => '你确定要激活所有选中的会员帐号吗？',
    'j_member_mulopDisable' => '你确定要冻结所有选中的会员帐号吗？',
    'j_member_mulopDelete' => '注意!该操作不可恢复.你确定要删除选中的会员吗？',
    'j_member_deleteItemMsg' => '你确定要删除该会员帐号吗？',
    //错误提示信息
    'e_member_ValueIsImproper' => '错误!非法的状态值!',
    'e_member_idIsEmpty' => '错误!没有指定的ID号!',
    'e_member_usernameIsEmpty' => '用户名不能为空',
    'e_member_usernameIsExist' => '该用户已经存在,请重新填写',
    'e_member_stateIsEmpty' => '请指定会员的状态',
    'e_member_groupIsEmpty' => '请指定会员的会员组',
    'e_member_emailIsEmpty' => '电子邮箱不能为空,请重新填写',
    'e_member_phoneIsEmpty' => '联系电话不能为空,请重新填写',
    'e_member_areaIsEmpty' => '请指定所在地区',
    'e_member_introIsEmpty' => '会员简介不能为空,请重新填写',
    'e_member_userpassIsEmpty' => '会员密码不能为空,请重新填写',
    'e_member_cuserpassIsEmpty' => '确认密码不能为空,请重新填写',
    'e_member_userpassUnmatched' => '输入的密码与确认密码不匹配.',
    'e_member_invalidationManipulation' => '操作失败!,原因未知...',
/**
 * MemberNotice
 */
    //页面提示的信息
    'p_memberNotice_showlistTitle' => '会员公告列表',
    'p_memberNotice_createTitle' => '发布会员公告',
    'p_memberNotice_editTitle' => '修改会员公告',
    'p_memberNotice_mulopEnable' => '发布',
    'p_memberNotice_mulopDisable' => '不发布',
    'p_memberNotice_mulopDelete' => '删除',
    //js提示信息
    'j_memberNotice_mulopEnable' => '你确定要发布所有的选中的公告吗？',
    'j_memberNotice_mulopDisable' => '你确定要不发布所有的选中的公告吗？',
    'j_memberNotice_mulopDelete' => '注意!该操作不可恢复.你确定要删除所有的选中的公告吗？',
    'j_memberNotice_deleteItemMsg' => '你确定要删除该资讯吗？',
    //错误提示信息
    'e_memberNotice_ValueIsImproper' => '错误!非法的状态值!',
    'e_memberNotice_idIsEmpty' => '错误!没有指定的ID号!',
    'e_memberNotice_titleIsEmpty' => '主题不能为空',
    'e_memberNotice_titleIsExist' => '该主题已经存在,请重新填写',
    'e_memberNotice_langIsEmpty' => '请选择该公告的语言类型',
    'e_memberNotice_stateIsEmpty' => '请选择公告发布状态',
    'e_memberNotice_contentIsEmpty' => '公告内容不能为空',
    'e_memberNotice_invalidationManipulation' => '操作失败!,原因未知...',
/**
 * MemberGroup
 */
    //页面提示的信息
    'p_memberGroup_showlistTitle' => '会员组列表',
    'p_memberGroup_createTitle' => '新建会员组',
    'p_memberGroup_editTitle' => '修改会员组信息',
    'p_memberGroup_permissionsTitle' => '编辑会员组权限',
    'p_memberGroup_stateDisabled' => '已禁用',
    'p_memberGroup_stateEnabled' => '已启用',
    'p_memberGroup_stateDelete' => '已删除',
    'p_memberGroup_mulopDisable' => '禁用',
    'p_memberGroup_mulopEnable' => '启用',
    'p_memberGroup_mulopDelete' => '删除',
    //js提示信息
    'j_memberGroup_mulopEnable' => '你确定要激活所有选中的会员组吗？',
    'j_memberGroup_mulopDisable' => '你确定要冻结所有选中的会员组吗？',
    'j_memberGroup_mulopDelete' => '注意!该操作不可恢复.你确定要删除选中的会员组吗？',
    'j_memberGroup_deleteItemMsg' => '你确定要删除该会员组吗？',
    //错误提示信息
    'e_memberGroup_ValueIsImproper' => '错误!非法的状态值!',
    'e_memberGroup_idIsEmpty' => '错误!没有指定的ID号!',
    'e_memberGroup_invalidationManipulation' => '操作失败!,原因未知...',
    'e_memberGroup_titleIsEmpty' => '会员组标题不能为空',
    'e_memberGroup_titleIsExist' => '该会员组已经存在,请重新填写',
    'e_memberGroup_idIsEmpty' => '错误!没有指定的ID号!',
/**
 * SystemUser
 */
    //页面提示的信息
    'p_systemUser_showlistTitle' => '系统用户列表',
    'p_systemUser_createTitle' => '新建系统用户',
    'p_systemUser_editTitle' => '修改系统用户资料',
    'p_systemUser_stateDisabled' => '未激活',
    'p_systemUser_stateEnabled' => '已激活',
    'p_systemUser_stateDelete' => '已删除',
    'p_systemUser_mulopEnable' => '激活',
    'p_systemUser_mulopDisable' => '冻结',
    'p_systemUser_mulopDelete' => '删除',
    'p_systemUser_userpassHint' => '密码默认已隐藏,如果不想修改密码则将此处留空',
    //js提示信息
    'j_systemUser_mulopEnable' => '你确定要激活所有选中的用户帐号吗？',
    'j_systemUser_mulopDisable' => '你确定要冻结所有选中的用户帐号吗？',
    'j_systemUser_mulopDelete' => '注意!该操作不可恢复.你确定要删除选中的用户吗？',
    'j_systemUser_deleteItemMsg' => '你确定要删除该用户吗？',
    //错误提示信息
    'e_systemUser_ValueIsImproper' => '错误!非法的状态值!',
    'e_systemUser_idIsEmpty' => '错误!没有指定的ID号!',
    'e_systemUser_usernameIsEmpty' => '用户名不能为空',
    'e_systemUser_usernameIsExist' => '该用户已经存在,请重新填写',
    'e_systemUser_stateIsEmpty' => '请指定用户的状态',
    'e_systemUser_groupIsEmpty' => '请指定用户的用户组',
    'e_systemUser_emailIsEmpty' => '电子邮箱不能为空,请重新填写',
    'e_systemUser_phoneIsEmpty' => '联系电话不能为空,请重新填写',
    'e_systemUser_introIsEmpty' => '用户简介不能为空,请重新填写',
    'e_systemUser_userpassIsEmpty' => '用户密码不能为空,请重新填写',
    'e_systemUser_cuserpassIsEmpty' => '确认密码不能为空,请重新填写',
    'e_systemUser_userpassUnmatched' => '输入的密码与确认密码不匹配.',
    'e_systemUser_invalidationManipulation' => '操作失败!,原因未知...',
/**
 * SystemUserGroup
 */
    //页面提示的信息
    'p_systemUserGroup_showlistTitle' => '系统用户组列表',
    'p_systemUserGroup_createTitle' => '新建系统用户组',
    'p_systemUserGroup_editTitle' => '修改用户组信息',
    'p_systemUserGroup_permissionsTitle' => '编辑用户组权限',
    'p_systemUserGroup_stateDisabled' => '已禁用',
    'p_systemUserGroup_stateEnabled' => '已启用',
    'p_systemUserGroup_stateDelete' => '已删除',
    'p_systemUserGroup_mulopDisable' => '禁用',
    'p_systemUserGroup_mulopEnable' => '启用',
    'p_systemUserGroup_mulopDelete' => '删除',
    //js提示信息
    'j_systemUserGroup_mulopEnable' => '你确定要激活所有选中的用户组吗？',
    'j_systemUserGroup_mulopDisable' => '你确定要冻结所有选中的用户组吗？',
    'j_systemUserGroup_mulopDelete' => '注意!该操作不可恢复.你确定要删除选中的用户组吗？',
    'j_systemUserGroup_deleteItemMsg' => '你确定要删除该用户组吗？',
    //错误提示信息
    'e_systemUserGroup_ValueIsImproper' => '错误!非法的状态值!',
    'e_systemUserGroup_idIsEmpty' => '错误!没有指定的ID号!',
    'e_systemUserGroup_invalidationManipulation' => '操作失败!,原因未知...',
    'e_systemUserGroup_titleIsEmpty' => '用户组标题不能为空',
    'e_systemUserGroup_titleIsExist' => '该用户组已经存在,请重新填写',
    'e_systemUserGroup_idIsEmpty' => '错误!没有指定的ID号!',
    //SystemCategory
    'p_systemCategory_showlistTitle' => '系统分类列表',
    'p_systemCategory_createTitle' => '添加系统分类',
    'j_systemCategory_deleteItemMsg' => '在执行此操作时会把当前类别的所有子类别同时删除,你确定要删除吗？',
    'e_systemCategory_titleIsEmpty' => '请填写中文标题',

//系统参数设置
    'p_system_settingTitle' => '系统参数设置',
    'p_system_settingComplete' => '设置成功.!',
    'p_system_goBack' => '返回',
//权限设置
    'p_developer_PremissionsTitle' => '后台权限列表',
    'p_developer_PremissionsUpdateTitie' => '编辑后台权限定义',
    'p_developer_PremissionsAddTitle' => '定义新权限',
    'p_developer_PremissionsMulopDelete' => '删除',
    'j_developer_PremissionsMulopDelete' => '此操作不可回复.你确定要删除选中的权限吗?',
    'e_developer_invalidationManipulation' => '插入数据失败,原因未知',
    'e_developer_PremissionsIdIsEmpty' => '错误!没有指定任何项的ID',
    'e_developer_PremissionsTitleEmpty' => '权限标识不能为空',
    'e_developer_PremissionsTitleIsExist' => '你输入的权限标识已经存在',
    'e_developer_PremissionsDescriptionIsEmpty' => '请填写权限说明',
//Alipay(支付宝)
    'alipay' => array(
        'logistics_payment' => array(
            'SELLER_PAY' => '卖家支付',
            'BUYER_PAY' => '买家支付',
            'BUYER_PAY_AFTER_RECEIVE' => '货到付款',
        ),
        'logistics_type' => array(
            'POST' => '平邮',
            'EMS' => 'EMS',
            'EXPRESS' => '其他快递',
        ),
    ),
);
?>
