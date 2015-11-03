<?php
return array(
    'error_reporting' => E_ALL ^E_NOTICE,       //错误报告设置
    'timezone' => 'Asia/Shanghai',              //所在时区
    'ob_gzhandler' => true,                     //压缩输出
    'log_level' => L_DEBUG,                     //日志记录级别
    'core_sef_query_string' => false,            //是否需要使用搜索引擎友好QUERY_STRING
    
    'site_name' => 'http://www.zycfcw.com',                  //网站名称
    'upload_dir' => 'uploads',                  //上传目录
    'idel_timeout' => 7200,                     //会话失效时间
    'url_fix' => '/zyc/',          //URL修正
    'site_url' => 'http://'.$_SERVER['HTTP_HOST'],

    'dateFormat' => 'Y-m-d',

    'language_default' => 'zh',                 //默认语言
    'language_support' => array('zh'),     //网站已经支持的语言

    'module_default' => 'default',              //默认模块
    'action_default' => 'index',                //默认动作

    'module_login' => 'default',                //默认登录模块
    'action_login' => 'login',                  //默认登录动作

    'table_prefix' => 'fw_',                    //数据库表名前缀

    'groupPermissionsTab' => 'mb_group_priv',
    'permissionsTab' => 'mb_permissions',
    

    'database' => array(                        //数据库服务器信息
        'driver_support' => array('mysql', 'pdo', 'ado_access'),
        'driver' => 'mysql',                    //数据库驱动
        'host' => 'localhost',                  //服务器地址或是dsn
        'db' => 'zycfcw',                 //数据库名
        'user' => 'root',
        'pass' => '9986106062',
        'encoding' => 'utf8'                    //连接时使用的编码(仅对mysql有效
    ),

    //常用正则表达式
    're' => array(                              
        'is_username' => '/^[a-z0-9_]{3,20}$/',
        'is_email' => '/^[a-z0-9_\-\.]+@[a-zZ0-9_-]+\.[a-z0-9_-]+[a-z\.]+/'
    ),
    //邮件服务器信息
    /*'mail_server' => array(
        'host'      => 'mail.eyo.cn',
        'mailer'    => 'stmp',
        'smtpAuth'  => true,
        'charSet'   => 'utf-8',
        'encoding'  => 'base64',
        'from'      => 'mailserver@eyo.cn',
        'username'  => 'mailserver@eyo.cn',
        'password'  => 'mail988',
    ),*/
    'alipay' => array(
        //合作伙伴ID
        'partner' => '',
        //安全检验码
        'security_code' => '',
        //卖家邮箱
        'seller_email' => '',
        //字符编码格式  目前支持 GBK 或 utf-8
        '_input_charset' => 'utf-8',
        //加密方式  系统默认(不要修改)
        'sign_type' => 'MD5',
        //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式(系统默认,不要修改)
        'transport' => 'http',
        //异步返回地址 需要填写完整的路径
        'notify_url' => '',
        //同步返回地址  需要填写完整大额路径
        'return_url' => 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/index.php?mod=order&act=alipay_return', 
        // 物流配送费用付款方式：SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
        'logistics_payment' => 'BUYER_PAY',
        // 物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
        'logistics_type' => 'EXPRESS',
        // 物流配送费用
        'logistics_fee' => '0'
    ),
    '99bill_com' => array(
        //人民币网关账户号
        ///请登录快钱系统获取用户编号，用户编号后加01即为人民币网关账户号。
        'merchantAcctId' => "",

        //支付方式.固定选择值
        ///只能选择00、10、11、12、13、14
        ///00：组合支付（网关支付页面显示快钱支持的各种支付方式，推荐使用）10：银行卡支付（网关支付页面只显示银行卡支付）.11：电话银行支付（网关支付页面只显示电话支付）.12：快钱账户支付（网关支付页面只显示快钱账户支付）.13：线下支付（网关支付页面只显示线下支付方式）
        'payType' => "00",

        //快钱的合作伙伴的账户号 ///如未和快钱签订代理合作协议，不需要填写本参数
        'pid' => "", ///合作伙伴在快钱的用户编号

        ///区分大小写.请与快钱联系索取
        'key' => ""
    )
);
?>
