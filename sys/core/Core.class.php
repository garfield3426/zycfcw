<?php

class Core {
     /**
     * 模块调度函数(主入口)
     */
    function run(){
        $request = Request::singleton();
        $config = Config::singleton();
     //  echo $_SERVER['REDIRECT_SCRIPT_URL'];
        //取得要实例化的模块和动作
        $module= strtolower($request->get('moduleName'));
        $action= strtolower($request->get('actionName'));
       
        
        if(empty($module)){
            $module= $config->get('module_default');
        }
        if(empty($action)){
            $action= $config->get('action_default');
        }
        
        define('APPROOT', APP_DIR.'/'.APP_ID.'/');
        define('CURRENT_MODULE', $module);
        define('CURRENT_ACTION', $action);
        $fileName = APPROOT."$module.$action.php";
       
        if(!file_exists($fileName)){
            Core::raiseMsg(_Error(0x010101, array("$module.$action")));
            return false;
        }
        
        include_once $fileName;

        if(!class_exists($action)){
            trigger_error(_Error(0x010102, array(__FUNCTION__, $fileName)));
            return false;
        }

        $thread= new $action();

      
        if(!is_a($thread, 'Action')){
            trigger_error(_Error(0x010103, array(__FUNCTION__, $action, $fileName)));
            return false;
        }
        
        if(!method_exists($thread, 'process')){
            trigger_error(_Error(0x010104, array(__FUNCTION__, $fileName)));
            return false;
        }
        
        if(ACT_OPEN != $thread->AuthLevel){
            if(!($GLOBALS['fw_session']->getUserId())){
			   $GLOBALS['fw_session']->setNextTo($config->get('site_url').$_SERVER['SCRIPT_URL']);
				if(substr($_SERVER['SCRIPT_URL'],0,7)=='/index-' ){
					Core::redirect(Core::getUrl('login','member',null,false,true));
				}else{
					Core::redirect(Core::getUrl($config->get('action_login'),$config->get('module_login')));
				}
                return false;
            }
            if((time() - $GLOBALS['fw_session']->getLastActTime()) > $config->get('idel_timeout')){
                $GLOBALS['fw_session']->end();
                Core::raiseMsg(_Error(0x010105),array(_Error(0x010106)=>Core::getUrl($config->get('action_login'), $config->get('module_login'))),true);
                return false;
            }
            if((ACT_NEED_AUTH == $thread->AuthLevel) && !Core::hasPermissions($module.'.'.$action)){
                Core::raiseMsg(_Error(0x010107));
                return false;
            }
        }
        $GLOBALS['fw_session']->updateLastActTime();
        if($config->get('ob_gzhandler')){
            ob_start("ob_gzhandler");
            $thread->process();
            ob_end_flush();
            return true;
        }
        
        $thread->process();
    }

    /**
     * 检查指定用户组是否有运行某个模块和模块的权限
     */
    function hasPermissions($perm){
        $gid = $GLOBALS['fw_session']->getGroupId();
        if(!is_numeric($gid)){
            return false;
        }
        $fileName = VAR_DIR."/group_priv/".APP_ID."/{$gid}";
        if(!file_exists($fileName)){
            if(!Core::tryCreatePermissionsCache($gid)){
                trigger_error(_Error(0x010108, array(__FUNCTION__, $gid, $fileName)));
            }
        }
        $privSet = unserialize(@file_get_contents($fileName));
        return in_array($perm, $privSet);
    }

   /**
     * 没有权限缓存,尝试创建.
     *
     * @param int $gid
     * @retrun bool
     */ 
    function tryCreatePermissionsCache($gid){
        $config = Config::singleton();
        $db = getDb();
        $sql = "select p.title as title from ".$config->get('table_prefix').$config->get('groupPermissionsTab')." gp left join ".$config->get('table_prefix').$config->get('permissionsTab')." p on gp.action_id=p.id where gp.group_id={$gid}";
        $rs = $db->Execute($sql);
        $data = array();
        while(!$rs->EOF){
            $data[] = $rs->fields['title'];
            $rs->MoveNext();
        }
        IO::writeFile(VAR_DIR."/group_priv/".APP_ID."/{$gid}", serialize($data));
        return file_exists(VAR_DIR."/group_priv/".APP_ID."/{$gid}") ? true : false;
    }

      /**
     * 页面重定向
     *
     * @param string $url url字符串
     */
    function redirect($url){
        header('Location:'.$url);
        exit();
    }

    /**
     * 输出信息到客户端,并中止程序执行
     *
     * @param string $msg 信息字符串
     * @param array $links 在信息字符串下显示的链接
     * 例:$links = array( '到a页'=>'a.html','到b页' => 'b.html');
     * @param bool $moveToTop 如果在子Frame中,设定是否要跳出子Frame显示
     */
    function raiseMsg($msg, $links= array(),$moveToTop = false){
        $v= array();
        $v['msg']= $msg;
        $v['toTop'] = !$moveToTop?'':'<script language="javascript">if(top.location !== self.location) top.location=self.location;</script>';
        if(count($links)){
            foreach($links as $key => $val){
                $v['links'] .= '&nbsp;<a href="'.$val.'">'.$key.'</a>';
            }
        }
        include_once(APP_DIR.'/'.APP_ID.'/'.LANGUAGE.'/message.htm');
        exit();
    }

     /**
     * 日志记录
     *
     * @param int $level
     * @param string $msg
     */ 
    function log($level, $msg){
		$userid = $GLOBALS['fw_session']?$GLOBALS['fw_session']->getUserId():'1';
        $config = Config::singleton();
        $db = getDb();
        $module = defined('CURRENT_MODULE') ? CURRENT_MODULE : 'Core';
        $action = defined('CURRENT_ACTION') ? CURRENT_ACTION : 'run';
        $log = array(
            'lev' => $level,
            'app' => APP_ID,
            'module' => $module,
            'act' => $action,
            'msg' => $msg,
            'userid' => $userid,
            'put_time' => time(),
        );
        $sql = "select * from ".$config->get('table_prefix')."log where id=-1";
        $sql = $db->GetInsertSQL($db->Execute($sql), $log);
        return $db->Execute($sql);
    }

     /**
     * 生成一个影射到指定模块和动作的URL
     *
     * @param string $action 动作标识字串
     * @param string $module 模块标识字串
     * @param array $reqdata 附加到url的参数
     * @param bool $useQueryCache 是否使用缓存中的QueryData(被保存在SESSION中)
     *
     * @return string $url URL字串
     */
    function getUrl($action= null, $module= null, $reqdata= null, $useQueryCache= false, $header= false){
    	if($header){
    		$header='/index-';
    	}else{
    		$header='/admin-';
    	}
    	
        $config = Config::singleton();
        $url= '';
        if(is_array($reqdata) && $useQueryCache && is_array($_SESSION['query_data'])){
            $reqdata= array_merge($_SESSION['query_data'], $reqdata);
        } elseif($useQueryCache && is_array($_SESSION['query_data'])){
            $reqdata= stripQuotes($_SESSION['query_data']);
        }
        //检查当前动作
        if(!$action){
            if(!defined('CURRENT_ACTION')){
                $action = $config->get('action_default');
            }else{
                $action = CURRENT_ACTION;
            }
        }
        //检查当前模块
        if(!$module){
            if(!defined('CURRENT_MODULE')){
                $module = $config->get('module_default');
            }else{
                $module = CURRENT_MODULE;
            }
        }
        //根据配置文件设置URL
        if($config->get('core_sef_query_string')){
            if(is_array($reqdata)){
                foreach($reqdata as $k => $v){
                    $url .= '-'.urlencode($k).'-'.urlencode($v);
                }
            }
            $url = $header.$module.'-'.$action.$url.'.html';
        }else{
            if(is_array($reqdata)){
                foreach($reqdata as $k => $v){
                    $url .= '-'.urlencode($k).'-'.urlencode($v);
                }
            }
            $url = $header.$module.'-'.$action.$url.'.html';
        }
        return $config->get('site_url').$url;
    }
}
?>
